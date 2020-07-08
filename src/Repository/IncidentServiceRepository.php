<?php

namespace App\Repository;

use App\Entity\IncidentService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IncidentService|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncidentService|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncidentService[]    findAll()
 * @method IncidentService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncidentService::class);
    }

    // /**
    //  * @return IncidentService[] Returns an array of IncidentService objects
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
    public function findOneBySomeField($value): ?IncidentService
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
