<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Service::class);
  }

  /**
    * @return Service Returns a Service object by guid
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
    * @return Service[] Returns all non deleted Service objects
  */
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }
}
