<?php

namespace App\Repository;

use App\Entity\CustomMetricDatapoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomMetricDatapoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomMetricDatapoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomMetricDatapoint[]    findAll()
 * @method CustomMetricDatapoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomMetricDatapointRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, CustomMetricDatapoint::class);
  }

  /**
   * @return CustomMetricDatapoint[] Returns an array of CustomMetricDatapoint objects after timestamp for metric
   */
  public function findAllAfterTimestampWithMetric($timestamp, $metricId)
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.created >= :timestamp')
      ->andWhere('c.metric = :metricId')
      ->setParameter('timestamp', $timestamp)
      ->setParameter('metricId', $metricId)
      ->orderBy('c.created', 'ASC')
      ->getQuery()
      ->getResult();
  }

  /**
   * @return CustomMetricDatapoint[] Returns an array of CustomMetricDatapoint objects before timestamp for metric
   */
  public function findAllBeforeTimestampWithMetric($timestamp, $metricId)
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.created < :timestamp')
      ->andWhere('c.metric = :metricId')
      ->setParameter('timestamp', $timestamp)
      ->setParameter('metricId', $metricId)
      ->orderBy('c.created', 'DESC')
      ->setMaxResults(1)
      ->getQuery()
      ->getResult();
  }
}
