<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findSellable(): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->where($qb->expr()->isNotNull('i.price'))
            ->orderBy('i.id');

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
}
