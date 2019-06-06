<?php

namespace App\Repository;

use App\Entity\Incident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Incident|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incident|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incident[]    findAll()
 * @method Incident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Incident::class);
  }

  /**
    * @return Incident Returns a Incident object by guid
  */
  public function findByGuid($guid)
  {
    return $this->createQueryBuilder('i')
      ->andWhere('i.guid = :guid')
      ->andWhere('i.deletedOn is NULL')
      ->setParameter('guid', $guid)
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
    * @return Incident[] Returns all non deleted Incident objects
  */
  public function findAllNotDeleted()
  {
    return $this->createQueryBuilder('i')
      ->andWhere('i.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Incident[] Return all active incidents
  */
  public function findAllActiveIncidents()
  {
    return $this->createQueryBuilder('i')
      ->innerJoin('i.status', 'incident_status')
      ->andWhere('incident_status.type != :type')
      ->setParameter('type', 'ok')
      ->andWhere('i.deletedOn is NULL')
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Incident Return last incident that occurred
   */
  public function findLastIncident()
  {
    return $this->createQueryBuilder('i')
      ->andWhere('i.deletedOn is NULL')
      ->orderBy('i.occurred', 'DESC')
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
