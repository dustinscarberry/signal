<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
      * @return User Returns a User object by hashId
    */
    public function findByHashId($hashId)
    {
      return $this->createQueryBuilder('u')
        ->andWhere('u.hashId = :hashId')
        ->setParameter('hashId', $hashId)
        ->andWhere('u.deletedOn is NULL')
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
      * @return User Returns a User object by username
    */
    public function findByUsername($username)
    {
      return $this->createQueryBuilder('u')
        ->andWhere('u.username = :username')
        ->setParameter('username', $username)
        ->andWhere('u.deletedOn is NULL')
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
      * @return User[] Returns all non deleted User objects
    */
    public function findAllNotDeleted()
    {
      return $this->createQueryBuilder('u')
        ->andWhere('u.deletedOn is NULL')
        ->getQuery()
        ->getResult();
    }
}
