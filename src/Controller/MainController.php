<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Form\OrderSimpleType;
use App\Repository\ItemRepository;
use App\Repository\OrderLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/sales', name: 'sales_index', methods: ['GET', 'POST'])]
    public function sales(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        foreach ($entityManager->getRepository(Item::class)->findSellable() as $item) {
            $order->addLine(new OrderLine($order, $item));
        }
        $formParameters = [];
        if (!$this->isGranted('ROLE_USER')) {
            $formParameters['action'] = '#';
        }
        $orderForm = $this->createForm(OrderSimpleType::class, $order, $formParameters);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            if ($this->isGranted('ROLE_USER')) {
                $order->setCreatedAt(new \DateTime());
                $entityManager->persist($order);
                $entityManager->flush();
            }
            return $this->redirectToRoute('sales_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('sales.html.twig', [
            'form' => $orderForm,
        ]);
    }

    #[Route('/production', name: 'production_index', methods: ['GET'])]
    public function production(ItemRepository $itemRepository): Response
    {
        return $this->render('production.html.twig', [
            'items' => $itemRepository->findForProduction(),
        ]);
    }

    #[Route('/production/events', name: 'production_events', methods: ['GET'])]
    public function productionEvents(Request $request, OrderLineRepository $orderLineRepository): Response
    {
        $lastUpdate = new \DateTimeImmutable($request->headers->get('last-event-id', 'now'));
        $retry = $this->getParameter('production_sse_frequency');
        $response = new Response(headers: ['Content-Type' => 'text/event-stream']);
        $quantities = $orderLineRepository->getToProduceQuantity($lastUpdate->modify('+1 second'));
        return $this->render('production.txt.twig', [
            'quantities' => $quantities,
            'retry' => $retry
        ], $response);
    }
}
