<?php

namespace App\Repository;

use App\Entity\ServiceStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ServiceStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceStatus[]    findAll()
 * @method ServiceStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceStatusRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, ServiceStatus::class);
  }

  /**
    * @return ServiceStatus Returns a ServiceStatus object by guid
  */
  public function findByGuid($guid)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.guid = :guid')
      ->setParameter('guid', $guid)
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
