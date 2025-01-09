<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function searchBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
    {
        $qb = $this->createQueryBuilder('o');
        $qb ->leftJoin('o.paymentMethod', 'pm')->addSelect('pm')
            ->leftJoin('o.lines', 'ol')->addSelect('ol')
        ;

        if (!empty($criteria['from']) && empty($criteria['to'])) {
            $qb ->andWhere($qb->expr()->gte('o.createdAt', ':from'))
                ->setParameter(':from', $criteria['from']);
            unset($criteria['from']);
        } else if (!empty($criteria['to']) && empty($criteria('from'))) {
            $qb ->andWhere($qb->expr()->gte('o.createdAt', ':to'))
                ->setParameter(':to', $criteria['to']);
            unset($criteria['to']);
        } else if (!empty($criteria['from']) && !empty($criteria['to'])) {
            $qb ->andWhere($qb->expr()->between('o.createdAt', 'from', ':to'))
                ->setParameter(':from', $criteria['from'])
                ->setParameter(':to', $criteria['to']);
            unset($criteria['from']);
            unset($criteria['to']);
        }

        foreach ($criteria as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $marker = ':' . $field;
            if (is_string($value)) {
                if (preg_match('`^".*"$`', $value)) {
                    $value = trim($value, '"');
                } else if (preg_match('`^!`', $value)) {
                    $value = '%' . $value . '%';
                }
            }

            if (\is_iterable($value)) {
                $qb ->andWhere($qb->expr()->in($this->sanitizeField($field), $marker));
            } else if (is_object($value)) {
                $value = $value->getId();
                $qb ->andWhere($qb->expr()->like($this->sanitizeField($field), $marker));
            } else {
                $value = trim($value, "  \r\n\t\v\0");
                $qb ->andWhere($qb->expr()->like($this->sanitizeField($field), $marker));
            }
            $qb ->setParameter($marker, $value);
        }

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy($this->sanitizeField($field), $direction);
        }

        if (null != $limit) {
            $qb->setMaxResults($limit);
        }
        if (null != $offset) {
            $qb->setFirstResult($offset);
        }

        $paginator = new Paginator($qb);

        return $paginator;
    }

    private function sanitizeField($field)
    {
        switch ($field) {
            case 'id':
                $field = 'o.id';
                break;
            case 'paymentMethod':
                $field = 'pm.id';
            default:
                break;
        }

        return $field;
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
