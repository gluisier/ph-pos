<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
