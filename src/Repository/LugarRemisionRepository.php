<?php

namespace App\Repository;

use App\Entity\LugarRemision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LugarRemision|null find($id, $lockMode = null, $lockVersion = null)
 * @method LugarRemision|null findOneBy(array $criteria, array $orderBy = null)
 * @method LugarRemision[]    findAll()
 * @method LugarRemision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LugarRemisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LugarRemision::class);
    }

    // /**
    //  * @return LugarRemision[] Returns an array of LugarRemision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LugarRemision
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
