<?php

namespace App\Repository;

use App\Entity\Widget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Widget|null find($id, $lockMode = null, $lockVersion = null)
 * @method Widget|null findOneBy(array $criteria, array $orderBy = null)
 * @method Widget[]    findAll()
 * @method Widget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WidgetRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Widget::class);
  }

  /**
    * @return Widget[] Returns all Widget objects sorted by sortorder
  */
  public function findAllSorted()
  {
    return $this->createQueryBuilder('w')
      ->orderBy('w.sortorder', 'ASC')
      ->getQuery()
      ->getResult();
  }
}
