<?php

namespace App\Repository;

use App\Entity\Maintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Maintenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maintenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maintenance[]    findAll()
 * @method Maintenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenanceRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Maintenance::class);
  }

  /**
    * @return Maintenance Returns a Maintenance object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('m')
      ->andWhere('m.hashId = :hashId')
      ->andWhere('m.deletedOn is NULL')
      ->setParameter('hashId', $hashId)
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
    * @return Maintenance[] Returns all non deleted Maintenance objects
  */
  public function findAllNotDeleted($reverse, $maxRecords)
  {
    $query = $this->createQueryBuilder('m')
      ->andWhere('m.deletedOn is NULL');

    if ($reverse)
      $query->addOrderBy('m.scheduledFor', 'DESC');

    if ($maxRecords)
      $query->setMaxResults($maxRecords);

    return $query->getQuery()
      ->getResult();
  }

  /**
    * @return Maintenance[] Returns all past Maintenance objects
  */
  public function findAllPastMaintenance($reverse, $maxRecords)
  {
    $query = $this->createQueryBuilder('m')
      ->andWhere('m.anticipatedEnd < :currentTime')
      ->andWhere('m.deletedOn is NULL')
      ->setParameter('currentTime', time());

    if ($reverse)
      $query->addOrderBy('m.scheduledFor', 'DESC');

    if ($maxRecords)
      $query->setMaxResults($maxRecords);

    return $query->getQuery()
      ->getResult();
  }

  /**
    * @return Maintenance[] Returns all scheduled Maintenance objects
  */
  public function findAllScheduledMaintenance($reverse, $maxRecords)
  {
    $query = $this->createQueryBuilder('m')
      ->andWhere('m.scheduledFor > :currentTime')
      ->andWhere('m.deletedOn is NULL')
      ->setParameter('currentTime', time());

    if ($reverse)
      $query->addOrderBy('m.scheduledFor', 'DESC');

    if ($maxRecords)
      $query->setMaxResults($maxRecords);

    return $query->getQuery()
      ->getResult();
  }
}
