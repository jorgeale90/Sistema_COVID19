<?php

namespace App\Repository;

use App\Entity\AreaSalud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AreaSalud|null find($id, $lockMode = null, $lockVersion = null)
 * @method AreaSalud|null findOneBy(array $criteria, array $orderBy = null)
 * @method AreaSalud[]    findAll()
 * @method AreaSalud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaSaludRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AreaSalud::class);
    }

    public function findByMunicipio($municipio_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:AreaSalud m WHERE m.municipio = :municipio_id');
        $consulta->setParameter('municipio_id', $municipio_id);
        return $consulta->getArrayResult();
    }

    public function findByMunicipiocon($municipiocon_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:AreaSalud m WHERE m.municipio = :municipio_id');
        $consulta->setParameter('municipio_id', $municipiocon_id);
        return $consulta->getArrayResult();
    }

    // /**
    //  * @return AreaSalud[] Returns an array of AreaSalud objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AreaSalud
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
