<?php

namespace App\Repository;

use App\Entity\GoogleCalendarEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GoogleCalendarEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoogleCalendarEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoogleCalendarEvent[]    findAll()
 * @method GoogleCalendarEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoogleCalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GoogleCalendarEvent::class);
    }

    // /**
    //  * @return GoogleCalendarEvent[] Returns an array of GoogleCalendarEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoogleCalendarEvent
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
