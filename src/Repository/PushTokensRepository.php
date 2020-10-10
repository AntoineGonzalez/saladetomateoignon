<?php

namespace App\Repository;

use App\Entity\PushTokens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PushTokens|null find($id, $lockMode = null, $lockVersion = null)
 * @method PushTokens|null findOneBy(array $criteria, array $orderBy = null)
 * @method PushTokens[]    findAll()
 * @method PushTokens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PushTokensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PushTokens::class);
    }

    // /**
    //  * @return PushTokens[] Returns an array of PushTokens objects
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
    public function findOneBySomeField($value): ?PushTokens
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
