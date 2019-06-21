<?php

namespace App\Repository;

use App\Entity\CustomMetricDatapoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CustomMetricDatapoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomMetricDatapoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomMetricDatapoint[]    findAll()
 * @method CustomMetricDatapoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomMetricDatapointRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomMetricDatapoint::class);
    }

    // /**
    //  * @return CustomMetricDatapoint[] Returns an array of CustomMetricDatapoint objects
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
    public function findOneBySomeField($value): ?CustomMetricDatapoint
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
