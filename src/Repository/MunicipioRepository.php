<?php

namespace App\Repository;

use App\Entity\Municipio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Municipio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Municipio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Municipio[]    findAll()
 * @method Municipio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Municipio::class);
    }

    public function findByProvincia($provincia_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:Municipio m WHERE m.provincia = :provincia_id');
        $consulta->setParameter('provincia_id', $provincia_id);
        return $consulta->getArrayResult();
    }

    public function findByProvinciai($provincia_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:Municipio m WHERE m.provincia = :provincia_id');
        $consulta->setParameter('provincia_id', $provincia_id);
        return $consulta->getArrayResult();
    }

    public function findByProvinciaa($provincia_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:Municipio m WHERE m.provincia = :provincia_id');
        $consulta->setParameter('provincia_id', $provincia_id);
        return $consulta->getArrayResult();
    }

    public function findByProvinciaalo($provincia_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:Municipio m WHERE m.provincia = :provincia_id');
        $consulta->setParameter('provincia_id', $provincia_id);
        return $consulta->getArrayResult();
    }

    public function findByProvinciacon($provincia_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:Municipio m WHERE m.provincia = :provincia_id');
        $consulta->setParameter('provincia_id', $provincia_id);
        return $consulta->getArrayResult();
    }

    // /**
    //  * @return Municipio[] Returns an array of Municipio objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Municipio
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
