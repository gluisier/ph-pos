<?php

namespace App\Controller;

use App\Entity\Printer;
use App\Entity\User;
use App\Form\PrinterType;
use App\Repository\PrinterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/printer', name: 'printer_')]
final class PrinterController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PrinterRepository $printerRepository): Response
    {
        return $this->render('printer/index.html.twig', [
            'printers' => $printerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $printer = new Printer();
        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($printer);
            $entityManager->flush();

            return $this->redirectToRoute('printer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('printer/new.html.twig', [
            'printer' => $printer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(#[MapEntity] Printer $printer): Response
    {
        return $this->render('printer/show.html.twig', [
            'printer' => $printer,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Printer $printer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrinterType::class, $printer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('printer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('printer/edit.html.twig', [
            'printer' => $printer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity] Printer $printer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$printer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($printer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('printer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/config/{id?}', name: 'config', methods: ['GET'], priority: 1)]
    public function config(Request $request, UserInterface $user, #[MapEntity] ?Printer $printer = null)
    {
        if (!$printer && (!$user instanceof User || !($printer = $user->getPrinter()))) {
            throw $this->createNotFoundException();
        }

        return $this->render('printer/config.js.twig', [
            'printer' => $printer,
            'user' => $user,
        ], new Response(headers: ['Content-Type' => 'text/javascript']));
    }
}
