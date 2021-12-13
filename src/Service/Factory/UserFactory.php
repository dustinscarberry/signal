<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Service\Generator\ApiTokenGenerator;

class UserFactory
{
  private $em;
  private $security;
  private $passwordEncoder;

  public function __construct(
    EntityManagerInterface $em,
    Security $security,
    UserPasswordHasherInterface $passwordEncoder
  )
  {
    $this->em = $em;
    $this->security = $security;
    $this->passwordEncoder = $passwordEncoder;
  }

  public function createUser($user)
  {
    //encode password and add roles
    $encodedPassword = $this->passwordEncoder->hashPassword($user, $user->getPassword());
    $user->setPassword($encodedPassword);
    $user->setRoles(['ROLE_ADMIN']);
    $user = $this->regenerateApiToken($user);
    $this->em->persist($user);
    $this->em->flush();
  }

  public function updateUser($user, $newPassword = null)
  {
    //change password if new provided
    if ($newPassword)
      $user->setPassword($this->passwordEncoder->hashPassword($user, $newPassword));

    //flush user object
    $this->em->flush();
  }

  public function deleteUser($user)
  {
    $user->setDeletedOn(time());
    $user->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function regenerateApiToken($user): User
  {
    $token = ApiTokenGenerator::generate();
    $user->setApiToken($token);
    $this->em->flush();
    return $user;
  }

  public function getUser($hashId)
  {
    return $this->em
      ->getRepository(User::class)
      ->findByHashId($hashId);
  }

  public function getUserByUsername($username)
  {
    return $this->em
      ->getRepository(User::class)
      ->findByUsername($username);
  }

  public function getUsers()
  {
    return $this->em
      ->getRepository(User::class)
      ->findAllNotDeleted();
  }
}
