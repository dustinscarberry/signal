<?php

namespace App\Repository;

use App\Entity\Maintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Maintenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maintenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maintenance[]    findAll()
 * @method Maintenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenanceRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
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
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('m')
      ->andWhere('m.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }

  /**
    * @return Maintenance[] Returns all scheduled Maintenance objects
  */
  public function findAllScheduledMaintenance()
  {
    return $this->createQueryBuilder('m')
      ->andWhere('m.scheduledFor > :currentTime')
      ->setParameter('currentTime', time())
      ->andWhere('m.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }
}
