<?php

namespace App\Repository;

use App\Entity\ConsultorioMedico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConsultorioMedico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultorioMedico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultorioMedico[]    findAll()
 * @method ConsultorioMedico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultorioMedicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultorioMedico::class);
    }

    public function findByAreaS($areasalud_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:ConsultorioMedico m WHERE m.areasalud = :areasalud_id');
        $consulta->setParameter('areasalud_id', $areasalud_id);
        return $consulta->getArrayResult();
    }

    // /**
    //  * @return ConsultorioMedico[] Returns an array of ConsultorioMedico objects
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
    public function findOneBySomeField($value): ?ConsultorioMedico
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
