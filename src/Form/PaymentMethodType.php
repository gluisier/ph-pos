<?php

namespace App\Form;

use App\Entity\PaymentMethod;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends CategoryType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', null, [
                'label_format' => 'app.fields.%name%',
                'help' => 'app.fields.payment_method.id.help',
                'help_html' => true,
            ])
        ;
        parent::buildForm($builder, $options);
        $builder
            ->add('available', null, [
                'label_format' => 'app.fields.payment_method.%name%.label.long',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethod::class,
        ]);
    }
}
