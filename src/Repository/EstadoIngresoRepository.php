<?php

namespace App\Repository;

use App\Entity\EstadoIngreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EstadoIngreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoIngreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoIngreso[]    findAll()
 * @method EstadoIngreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoIngresoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoIngreso::class);
    }

    // /**
    //  * @return EstadoIngreso[] Returns an array of EstadoIngreso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstadoIngreso
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
