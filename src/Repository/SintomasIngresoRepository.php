<?php

namespace App\Repository;

use App\Entity\SintomasIngreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SintomasIngreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method SintomasIngreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method SintomasIngreso[]    findAll()
 * @method SintomasIngreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SintomasIngresoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SintomasIngreso::class);
    }

    // /**
    //  * @return SintomasIngreso[] Returns an array of SintomasIngreso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SintomasIngreso
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
