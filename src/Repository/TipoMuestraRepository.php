<?php

namespace App\Repository;

use App\Entity\TipoMuestra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoMuestra|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoMuestra|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoMuestra[]    findAll()
 * @method TipoMuestra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoMuestraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoMuestra::class);
    }

    // /**
    //  * @return TipoMuestra[] Returns an array of TipoMuestra objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoMuestra
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
