<?php

namespace App\Repository;

use App\Entity\PurshaseMenus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PurshaseMenus|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurshaseMenus|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurshaseMenus[]    findAll()
 * @method PurshaseMenus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurshaseMenusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurshaseMenus::class);
    }

    // /**
    //  * @return PurshaseMenus[] Returns an array of PurshaseMenus objects
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
    public function findOneBySomeField($value): ?PurshaseMenus
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
