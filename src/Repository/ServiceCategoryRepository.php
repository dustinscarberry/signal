<?php

namespace App\Repository;

use App\Entity\ServiceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ServiceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCategory[]    findAll()
 * @method ServiceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCategoryRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, ServiceCategory::class);
  }

  /**
    * @return ServiceCategory Returns a ServiceCategory object by guid
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
    * @return ServiceCategory[] Returns all non deleted ServiceCategory objects
  */
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }
}
