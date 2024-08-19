<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @return Item[] Returns an array of Item objects
     */
    public function findForSales(): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->where($qb->expr()->eq('i.available', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->eq('i.separatelySellable', $qb->expr()->literal(true)))
            ->addOrderBy('i.position');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Item[] Returns an array of Item objects
     */
    public function findForProduction(): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->leftJoin('i.orders', 'o')
            ->addSelect('SUM(o.quantity) AS quantity')
            ->where($qb->expr()->isNotNull('i.price'))
            ->andWhere($qb->expr()->eq('i.ticket', $qb->expr()->literal(true)))
            ->groupBy('i.id');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Item[] Returns an array of Item objects
     */
    public function findForPrices()
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->innerJoin('i.category', 'c', Join::WITH, $qb->expr()->eq('c.public', $qb->expr()->literal(true)))->addSelect('c')
            ->leftJoin('i.attributes', 'a')->addSelect('a')
            ->leftJoin('a.items', 'd', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('d.available', $qb->expr()->literal(true)),
                $qb->expr()->eq('d.public', $qb->expr()->literal(true))
            ))->addSelect('d')
            ->leftJoin('i.variants', 'v', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('v.available', $qb->expr()->literal(true)),
                $qb->expr()->eq('v.public', $qb->expr()->literal(true))
            ))
            ->leftJoin('i.composedOf', 'packed')->addSelect('packed')
            ->where($qb->expr()->eq('i.available', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->eq('i.public', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->isNull('i.variantOf'))
            ->addOrderBy('c.position')
            ->addOrderBy('i.position')
            ->addOrderBy('a.position')
            ->addOrderBy('d.position');

        return $qb->getQuery()->getResult();
    }
}
