<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\PaymentMethod;
use App\Repository\CategoryRepository;
use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => '📖', // Cannot be translated!?
                'expanded' => true,
                'multiple' => false,
                'label' => false,
                'choice_label' => 'label',
                'query_builder' => function (CategoryRepository $repo) {
                    $qb = $repo->createQueryBuilder('c');
                    $qb ->innerJoin('c.items', 'i', Join::WITH, $qb->expr()->eq('i.separatelySellable', $qb->expr()->literal(true)))
                        ->addOrderBy($qb->expr()->asc('c.position'));

                    return $qb;
                },
                'choice_attr' => function (Category $choice) {
                    return [
                        // label_attr is not dynamic as of 7.1.3
                        'style' => 'background-color: ' . $choice->getColour(),
                        'title' => $choice->getTitle(),
                    ];
                },
                'translation_domain' => false,
            ])
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
            ->add('paymentMethod', EntityType::class, [
                'class' => PaymentMethod::class,
                'expanded' => true,
                'multiple' => false,
                'label' => false,
                'choice_label' => function (PaymentMethod $paymentMethod): string {
                    return $paymentMethod->getLabel() . ' ' . $paymentMethod->getTitle();
                },
                'query_builder' => function (PaymentMethodRepository $repo) {
                    $qb = $repo->createQueryBuilder('pm');
                    $qb ->where($qb->expr()->eq('pm.available', $qb->expr()->literal(true)))
                        ->addOrderBy($qb->expr()->asc('pm.position'));

                    return $qb;
                },
                'choice_attr' => function (PaymentMethod $choice) {
                    return [
                        // label_attr is not dynamic as of 7.1.3
                        'style' => 'background-color: ' . $choice->getColour()
                    ];
                },
                'translation_domain' => false,
            ])
            ->add('externalId', HiddenType::class)
            ->add('printDate', HiddenType::class)
        ;
        $builder
            ->get('printDate')
            ->addModelTransformer(new DateTimeToStringTransformer('Europe/Zurich', 'UTC', parseFormat: 'Y-m-d\TH:i:s.u\Z'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
