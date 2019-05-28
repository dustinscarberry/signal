<?php

namespace App\Repository;

use App\Entity\IncidentUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IncidentUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncidentUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncidentUpdate[]    findAll()
 * @method IncidentUpdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentUpdateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IncidentUpdate::class);
    }

    // /**
    //  * @return IncidentUpdate[] Returns an array of IncidentUpdate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IncidentUpdate
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
