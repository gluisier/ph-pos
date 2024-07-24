<?php

namespace App\Repository;

use App\Entity\OrderLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderLine>
 *
 * @method OrderLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderLine[]    findAll()
 * @method OrderLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderLine::class);
    }

    public function getToProduceQuantity(\DateTimeInterface $since)
    {
        $qb = $this->createQueryBuilder('ol');
        $qb ->select('PARTIAL ol.{order,item}, sum(ol.quantity) AS quantity, max(o.createdAt) AS lastOrder')
            ->innerJoin('ol.order', 'o')
            ->innerJoin('ol.item', 'i')
            ->where($qb->expr()->eq('i.ticket', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->between('o.createdAt', ':since', ':now'))
            ->groupBy('ol.item')
            ->setParameter(':since', $since)
            ->setParameter(':now', new \DateTime())
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
