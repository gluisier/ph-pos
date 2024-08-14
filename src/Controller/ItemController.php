<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Form\MassItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/item', name: 'item_')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/event', name: 'event', methods: ["GET", "POST"])]
    public function event(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $items = $entityManager->getRepository(Item::class)->findAll();

        $form = $formFactory->createNamedBuilder('event', FormType::class, ['items' => $items])
            ->add('items', CollectionType::class, [
                'entry_type' => MassItemType::class,
                'by_reference' => true,
                'label' => 'app.fields.event.items.label',
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->getForm();
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData()['items'] as $item) {
                $entityManager->persist($item);
            }
            $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/event.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/prices', name: 'prices', methods: ["GET"])]
    public function print(Request $request, ItemRepository $itemRepository)
    {
        return $this->render('item/print.html.twig', [
            'items' => $itemRepository->findForPrices(),
        ]);
    }
}
