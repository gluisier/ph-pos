<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\OrderLine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', null, [
                'mapped' => false,
                'label_format' => 'app.fields.order_line.%name%.label',
            ])
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'mapped' => false,
                'choice_label' => function(Item $item): string {
                    return $item->getTitle() . ' ' . ($item->getPrice() ? number_format($item->getPrice(), 2, ',') : '❌');
                },
                'label_format' => 'app.fields.order_line.%name%.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderLine::class,
            'empty_data' => function(FormInterface $form): OrderLine {
                if ($form->getData() instanceof OrderLine) {
                    return $form->getData();
                }

                $product = $form->get('item')->getData();
                $quantity = $form->get('quantity')->getData();
                $order = $form->getParent()->getParent()->getData();
                return new OrderLine($order, $product, $quantity);
            }
        ]);
    }
}
