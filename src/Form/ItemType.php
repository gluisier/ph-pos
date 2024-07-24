<?php

namespace App\Form;

use App\Entity\Item;
use App\Form\Type\NullableColorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('label', null, [
                'attr' => [
                    'class' => 'form-control-lg',
                ],
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'CHF',
                'scale' => 0,
                'input' => 'integer',
                'html5' => true,
            ])
            ->add('colour', NullableColorType::class)
            ->add('quantity')
            ->add('ticket')
            //->add('packedIn')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
