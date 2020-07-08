<?php

namespace App\Repository;

use App\Entity\ServiceStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceStatus[]    findAll()
 * @method ServiceStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceStatusRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ServiceStatus::class);
  }

  /**
    * @return ServiceStatus Returns a ServiceStatus object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.hashId = :hashId')
      ->setParameter('hashId', $hashId)
      ->andWhere('s.deletedOn is NULL')
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
    * @return ServiceStatus[] Returns all non deleted ServiceStatus objects
  */
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }
}
