<?php

namespace App\Form;

use App\Entity\Item;
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
            ->add('price', null, [
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('quantity', null, [
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('ticket', null, [
                'label_format' => 'app.fields.item.%name%.label',
            ])
            ->add('category', null, [
                'label_format' => 'app.fields.item.%name%.label',
                'choice_label' => 'title',
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
