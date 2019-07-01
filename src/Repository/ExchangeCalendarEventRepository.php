<?php

namespace App\Repository;

use App\Entity\ExchangeCalendarEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExchangeCalendarEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeCalendarEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeCalendarEvent[]    findAll()
 * @method ExchangeCalendarEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeCalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExchangeCalendarEvent::class);
    }

    // /**
    //  * @return ExchangeCalendarEvent[] Returns an array of ExchangeCalendarEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExchangeCalendarEvent
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
