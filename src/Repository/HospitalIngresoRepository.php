<?php

namespace App\Repository;

use App\Entity\HospitalIngreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HospitalIngreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method HospitalIngreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method HospitalIngreso[]    findAll()
 * @method HospitalIngreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HospitalIngresoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HospitalIngreso::class);
    }

    // /**
    //  * @return HospitalIngreso[] Returns an array of HospitalIngreso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HospitalIngreso
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
