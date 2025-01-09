<?php

namespace App\Form;

use App\Entity\PaymentMethod;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', IntegerType::class, [
                'label_format' => 'app.fields.id',
                'required' => false,
            ])
            ->add('paymentMethod', EntityType::class, [
                'class' => PaymentMethod::class,
                'required' => false,
                'label_format' => 'app.fields.order.%name%.label',
                'choice_label' => function (PaymentMethod $paymentMethod): string {
                    return $paymentMethod->getLabel() . ' ' . $paymentMethod->getTitle();
                },
            ])
            ->add('from', DateTimeType::class, [
                'required' => false,
                'label_format' => 'app.fields.order.createdAt.from',
            ])
            ->add('to', DateTimeType::class, [
                'required' => false,
                'label_format' => 'app.fields.order.createdAt.to',
            ])
        ;
    }
}
