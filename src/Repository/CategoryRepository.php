<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findForPrices()
    {
        $qb = $this->createQueryBuilder('c');
        $qb ->leftJoin('i.item', 'i', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('i.public', $qb->expr()->literal(true))),
                $qb->expr()->isNull('i.variantOf')
            )->addSelect('c')
            ->leftJoin('i.composedOf', 'packed')->addSelect('packed')
            ->leftJoin('i.variants', 'variant', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('variant.available', $qb->expr()->literal(true)),
                $qb->expr()->eq('variant.separatelySellable', $qb->expr()->literal(true))
            ))->addSelect('variant')
            ->where($qb->expr()->eq('c.public', $qb->expr()->literal(true)))
            ->orderBy('c.position')
            ->addOrderBy('i.position');

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
