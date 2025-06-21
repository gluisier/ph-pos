<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label_format' => 'app.fields.user.%name%.label',
            ])
            ->add('active', null, [
                'label_format' => 'app.fields.user.%name%.label',
            ])
            ->add('printer', null, [
                'query_builder' => function (\App\Repository\PrinterRepository $repo) {
                    $qb = $repo->createQueryBuilder('p');
                    $qb ->where($qb->expr()->neq('p.status', ':status'))
                        ->setParameter(':status', \App\Config\Printer\Status::STORED)
                    ;

                    return $qb;
                },
                'choice_label' => function (\App\Entity\Printer $choice) {
                    return $choice->getManufacturer()->value . ' ' . $choice->getModel();
                },
                'label_format' => 'app.fields.user.%name%.label.short',
            ])
            ->add('roles', CollectionType::class, [
                'label_format' => 'app.fields.user.%name%.label',
                'entry_options' => [
                    'label' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
