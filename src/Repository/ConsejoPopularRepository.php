<?php

namespace App\Repository;

use App\Entity\ConsejoPopular;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConsejoPopular|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsejoPopular|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsejoPopular[]    findAll()
 * @method ConsejoPopular[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsejoPopularRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsejoPopular::class);
    }

    public function findByMunicipioConsejoPopular($municipio_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:ConsejoPopular m WHERE m.municipio = :municipio_id');
        $consulta->setParameter('municipio_id', $municipio_id);
        return $consulta->getArrayResult();
    }

    // /**
    //  * @return ConsejoPopular[] Returns an array of ConsejoPopular objects
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
    public function findOneBySomeField($value): ?ConsejoPopular
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
