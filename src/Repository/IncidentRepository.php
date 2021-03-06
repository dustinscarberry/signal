<?php

namespace App\Repository;

use App\Entity\Incident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Incident|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incident|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incident[]    findAll()
 * @method Incident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Incident::class);
  }

  /**
    * @return Incident Returns a Incident object by hashId
  */
  public function findByHashId($hashId)
  {
    return $this->createQueryBuilder('i')
      ->andWhere('i.hashId = :hashId')
      ->andWhere('i.deletedOn is NULL')
      ->setParameter('hashId', $hashId)
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
    * @return Incident[] Returns all non deleted Incident objects
  */
  public function findAllNotDeleted($reverse, $maxRecords)
  {
    $query = $this->createQueryBuilder('i')
      ->andWhere('i.deletedOn is NULL');

    if ($reverse)
      $query->addOrderBy('i.occurred', 'DESC');

    if ($maxRecords)
      $query->setMaxResults($maxRecords);

    return $query->getQuery()
      ->getResult();
  }

  /**
    * @return Incident[] Returns all non deleted past Incident objects
  */
  public function findAllPastIncidents($reverse, $maxRecords)
  {
    $query = $this->createQueryBuilder('i')
      ->andWhere('i.anticipatedResolution < :currentTime')
      ->setParameter('currentTime', time())
      ->andWhere('i.deletedOn is NULL');

    if ($reverse)
      $query->addOrderBy('i.occurred', 'DESC');

    if ($maxRecords)
      $query->setMaxResults($maxRecords);

    return $query->getQuery()
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
