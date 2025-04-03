<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label_format' => 'app.fields.category.%name%.label',
            ])
            ->add('label', null, [
                'label_format' => 'app.fields.category.%name%.label.long',
            ])
            ->add('colour', ColorType::class, [
                'label_format' => 'app.fields.category.%name%.label',
            ])
            ->add('public', null, [
                'label_format' => 'app.fields.item.%name%.label.long',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
