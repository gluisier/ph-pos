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
    public function findForSales(bool $authenticated): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->leftJoin('i.attributes', 'a')->addSelect('a')
            ->leftJoin('i.composedOf', 'p')->addSelect('p')
            ->leftJoin('i.variants', 'v')->addSelect('v')
            ->where($qb->expr()->eq('i.available', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->eq('i.separatelySellable', $qb->expr()->literal(true)))
            ->addOrderBy('i.position');

        if (!$authenticated) {
            $qb->andWhere($qb->expr()->eq('i.public', $qb->expr()->literal(true)));
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Item[] Returns an array of Item objects
     */
    public function findForProduction(?\DateTimeInterface $since = null): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->leftJoin('i.orders', 'o1')
            ->leftJoin('i.composing', 'cmp')
            ->leftJoin('cmp.orders', 'o2')
            ->addSelect('SUM(COALESCE(o2.quantity, o1.quantity, 0)) AS quantity')
            ->where($qb->expr()->eq('i.available', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('i.ticket', $qb->expr()->literal(true)),
                $qb->expr()->eq('cmp.ticket', $qb->expr()->literal(true))
            ))
            ->andWhere($qb->expr()->not($qb->expr()->exists(
                $this->createQueryBuilder('sub')->where($qb->expr()->eq('sub.variantOf', 'i.id'))
            )))
            ->groupBy('i.id')
            ->orderBy($qb->expr()->desc('i.category'))
            ->addOrderBy($qb->expr()->asc('i.position'));

        if ($since) {
            $qb ->leftJoin('o1.order', 'ro1')
                ->leftJoin('o2.order', 'ro2')
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->gte('ro1.createdAt', ':since'),
                    $qb->expr()->gte('ro2.createdAt', ':since')
                ))
                ->setParameter(':since', $since);
        }

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
