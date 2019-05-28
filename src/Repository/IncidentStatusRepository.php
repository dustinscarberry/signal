<?php

namespace App\Repository;

use App\Entity\IncidentStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IncidentStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncidentStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncidentStatus[]    findAll()
 * @method IncidentStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
      parent::__construct($registry, IncidentStatus::class);
    }

    /**
      * @return IncidentStatus Returns a IncidentStatus object by guid
    */
    public function findByGuid($guid)
    {
      return $this->createQueryBuilder('i')
        ->andWhere('i.guid = :guid')
        ->andWhere('i.deletedOn is NULL')
        ->setParameter('guid', $guid)
        ->getQuery()
        ->getSingleResult();
    }

    /**
      * @return IncidentStatus[] Returns all non deleted IncidentStatus objects
    */
    public function findAllNotDeleted()
    {
      return $this->createQueryBuilder('i')
        ->andWhere('i.deletedOn is NULL')
        ->getQuery()
        ->getResult();
    }
}
