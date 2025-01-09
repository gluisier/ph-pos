<?php

namespace App\Controller;

use App\Entity\PaymentMethod;
use App\Form\PaymentMethodType;
use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/payment-method', name: 'payment_method_')]
class PaymentMethodController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PaymentMethodRepository $paymentMethodRepository): Response
    {
        return $this->render('payment_method/index.html.twig', [
            'payment_methods' => $paymentMethodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paymentMethod = new PaymentMethod();
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paymentMethod);
            $entityManager->flush();

            return $this->redirectToRoute('payment_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment_method/new.html.twig', [
            'payment_method' => $paymentMethod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(#[MapEntity] PaymentMethod $paymentMethod): Response
    {
        return $this->render('payment_method/show.html.twig', [
            'payment_method' => $paymentMethod,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] PaymentMethod $paymentMethod, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('payment_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment_method/edit.html.twig', [
            'payment_method' => $paymentMethod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity] PaymentMethod $paymentMethod, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentMethod->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($paymentMethod);
            $entityManager->flush();
        }

        return $this->redirectToRoute('payment_method_index', [], Response::HTTP_SEE_OTHER);
    }
}
