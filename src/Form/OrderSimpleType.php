<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderLine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => '📋', // Cannot be translated!?
                'expanded' => true,
                'multiple' => false,
                'label' => false,
                'choice_label' => 'label',
                'choice_attr' => function (Category $choice) {
                    return [
                        // label_attr is not dynamic as of 7.1.3
                        'style' => 'background-color: ' . $choice->getColour(),
                        'title' => $choice->getTitle(),
                    ];
                },
                'translation_domain' => false,
            ])
            ->add('lines', CollectionType::class, [
                'entry_type' => OrderLineSimpleType::class,
                'label' => false,
                'allow_add' => false,
                'allow_delete' => true,
                'delete_empty' => function(?OrderLine $line = null): bool {
                    return null === $line || $line->getQuantity() == 0;
                },
                'entry_options' => [
                    'label' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
