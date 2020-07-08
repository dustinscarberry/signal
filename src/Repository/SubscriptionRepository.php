<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Subscription::class);
  }

  /**
    * @return Subscription Returns a Subscription object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('s')
      ->andWhere('s.hashId = :hashId')
      ->setParameter('hashId', $hashId)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
