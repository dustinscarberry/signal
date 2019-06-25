<?php

namespace App\Repository;

use App\Entity\CustomMetric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CustomMetric|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomMetric|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomMetric[]    findAll()
 * @method CustomMetric[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomMetricRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, CustomMetric::class);
  }

  /**
    * @return CustomMetric Returns a CustomMetric object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('m')
      ->andWhere('m.hashId = :hashId')
      ->setParameter('hashId', $hashId)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
