<?php

namespace App\Repository;

use App\Entity\IncidentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IncidentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncidentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncidentType[]    findAll()
 * @method IncidentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IncidentType::class);
    }

    // /**
    //  * @return IncidentType[] Returns an array of IncidentType objects
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
    public function findOneBySomeField($value): ?IncidentType
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
