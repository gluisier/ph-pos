<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Item;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends CategoryType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('public', null, [
                'label_format' => 'app.fields.item.%name%.label.short',
                'label_attr' => [
                    'title' => 'app.fields.item.public.label.long',
                ],
            ])
            ->add('price', null, [
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('stock', null, [
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('ticket', null, [
                'label_format' => 'app.fields.item.%name%.label.short',
                'label_attr' => [
                    'title' => 'app.fields.item.ticket.label.long',
                ],
            ])
            ->add('available', null, [
                'label_format' => 'app.fields.item.%name%.label.short',
                'label_attr' => [
                    'title' => 'app.fields.item.available.label.long',
                ],
            ])
            ->add('separatelySellable', null, [
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('category', null, [
                'placeholder' => 'app.fields.item.category.placeholder',
                'choice_label' => function (Category $item): string {
                    return $item->getTitle() . ' ' . $item->getLabel();
                },
                'choice_attr' => [
                    'style' => 'background-color: aliceblue',
                ],
                'label_format' => 'app.fields.item.%name%.label.long',
            ])
            ->add('variantOf', null, [
                'choice_label' => function (Item $item): string {
                    return $item->getTitle() . ' ' . $item->getLabel() . ' ' . ($item->isAvailable() ? number_format($item->getPrice(), 2, ',') : '(non vendu)');
                },
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('attributes', null, [
                'multiple' => true,
                'expanded' => 'true',
                'query_builder' => function (CategoryRepository $repo) {
                    $qb = $repo->createQueryBuilder('a');
                    $qb ->innerJoin('a.items', 'v', \Doctrine\ORM\Query\Expr\Join::WITH, $qb->expr()->eq('v.separatelySellable', $qb->expr()->literal(false)))
                        ->where($qb->expr()->eq('a.public', $qb->expr()->literal(false)));

                    return $qb;
                },
                'choice_label' => function (Category $item): string {
                    return $item->getTitle() . ' ' . $item->getLabel();
                },
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('composedOf', CollectionType::class, [
                'entry_type' => EntityType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'class' => Item::class,
                    'choice_label' => function (Item $item): string {
                        return $item->getTitle() . ' ' . $item->getLabel() . ' ' . ($item->isAvailable() ? number_format($item->getPrice(), 2, ',') : '(non vendu)');
                    },
                    'label' => false,
                ],
                'label_format' => 'app.fields.item.%name%.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
