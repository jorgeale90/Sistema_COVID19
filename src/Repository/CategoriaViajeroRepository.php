<?php

namespace App\Repository;

use App\Entity\CategoriaViajero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategoriaViajero|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriaViajero|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriaViajero[]    findAll()
 * @method CategoriaViajero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaViajeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriaViajero::class);
    }

    // /**
    //  * @return CategoriaViajero[] Returns an array of CategoriaViajero objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriaViajero
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
