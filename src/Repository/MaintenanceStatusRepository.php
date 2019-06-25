<?php

namespace App\Repository;

use App\Entity\MaintenanceStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaintenanceStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaintenanceStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaintenanceStatus[]    findAll()
 * @method MaintenanceStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenanceStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaintenanceStatus::class);
    }

    /**
      * @return MaintenanceStatus Returns a MaintenanceStatus object by hashId
    */
    public function findByHashId($hashId)
    {
      return $this->createQueryBuilder('m')
        ->andWhere('m.hashId = :hashId')
        ->setParameter('hashId', $hashId)
        ->andWhere('m.deletedOn is NULL')
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
      * @return MaintenanceStatus[] Returns all non deleted MaintenanceStatus objects
    */
    public function findAllNotDeleted()
    {
      return $this->createQueryBuilder('m')
        ->andWhere('m.deletedOn is NULL')
        ->getQuery()
        ->getResult();
    }
}
