<?php

namespace App\Form;

use App\Config\Printer\Manufacturer;
use App\Config\Printer\Status;
use App\Entity\Printer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrinterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', null, [
                'label_format' => 'app.fields.id',
            ])
            ->add('model', null, [
                'label_format' => 'app.fields.printer.%name%.label',
            ])
            ->add('ip', null, [
                'label_format' => 'app.fields.printer.%name%.label',
            ])
            ->add('port', null, [
                'label_format' => 'app.fields.printer.%name%.label',
            ])
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'label_format' => 'app.fields.printer.%name%.label.short',
                'expanded' => true,
                'choice_attr' => fn($choice) => [
                    'title' => 'app.fields.printer.status.' . $choice->value . '.label.long',
                    'class' => 'btn-check pwet-choice'
                ],
            ])
            ->add('manufacturer', EnumType::class, [
                'class' => Manufacturer::class,
                'label_format' => 'app.fields.printer.%name%.label',
                'choice_label' => 'value',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Printer::class,
        ]);
    }
}
