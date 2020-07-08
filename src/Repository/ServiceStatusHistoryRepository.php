<?php

namespace App\Repository;

use App\Entity\ServiceStatusHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceStatusHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceStatusHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceStatusHistory[]    findAll()
 * @method ServiceStatusHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceStatusHistoryRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ServiceStatusHistory::class);
  }

  /**
   * @return ServiceStatusHistory[] Returns an array of ServiceStatusHistory objects starting on timestamp
   */
  public function findAllAfterTimestamp($timestamp)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.created >= :timestamp')
      ->setParameter('timestamp', $timestamp)
      ->orderBy('s.created', 'ASC')
      ->getQuery()
      ->getResult();
  }

  /**
   * @return ServiceStatusHistory[] Returns an array of ServiceStatusHistory objects before timestamp
   */
  public function findAllBeforeTimestamp($timestamp)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.created < :timestamp')
      ->setParameter('timestamp', $timestamp)
      ->orderBy('s.created', 'DESC')
      ->setMaxResults(50)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return ServiceStatusHistory[] Returns an array of ServiceStatusHistory objects after timestamp for service
   */
  public function findAllAfterTimestampWithService($timestamp, $serviceId)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.created >= :timestamp')
      ->andWhere('s.service = :serviceId')
      ->setParameter('timestamp', $timestamp)
      ->setParameter('serviceId', $serviceId)
      ->orderBy('s.created', 'ASC')
      ->getQuery()
      ->getResult();
  }

  /**
   * @return ServiceStatusHistory[] Returns an array of ServiceStatusHistory objects before timestamp for service
   */
  public function findAllBeforeTimestampWithService($timestamp, $serviceId)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.created < :timestamp')
      ->andWhere('s.service = :serviceId')
      ->setParameter('timestamp', $timestamp)
      ->setParameter('serviceId', $serviceId)
      ->orderBy('s.created', 'DESC')
      ->setMaxResults(1)
      ->getQuery()
      ->getResult();
  }
}
