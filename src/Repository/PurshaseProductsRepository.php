<?php

namespace App\Repository;

use App\Entity\PurshaseProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PurshaseProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurshaseProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurshaseProducts[]    findAll()
 * @method PurshaseProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurshaseProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurshaseProducts::class);
    }

    // /**
    //  * @return PurshaseProducts[] Returns an array of PurshaseProducts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PurshaseProducts
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
