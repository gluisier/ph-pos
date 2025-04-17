<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MassItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('available', CheckboxType::class, [
                'required' => false,
                'label_format' => 'app.fields.item.%name%.label.short',
                'label_attr' => [
                    'title' => 'app.fields.item.available.label.long',
                ],
            ])
            ->add('price', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'app.fields.item.price.label'
                ]
            ])
            ->add('stock', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'app.fields.item.stock.label'
                ]
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
