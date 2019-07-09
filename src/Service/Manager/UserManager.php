<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Service\Generator\ApiTokenGenerator;

class UserManager
{
  private $em;
  private $security;
  private $passwordEncoder;

  public function __construct(
    EntityManagerInterface $em,
    Security $security,
    UserPasswordEncoderInterface $passwordEncoder
  )
  {
    $this->em = $em;
    $this->security = $security;
    $this->passwordEncoder = $passwordEncoder;
  }

  public function createUser($user)
  {
    //encode password and add roles
    $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
    $user->setPassword($encodedPassword);
    $user->setRoles(['ROLE_ADMIN']);
    $this->em->persist($user);
    $this->em->flush();
  }

  public function updateUser($user, $newPassword = null)
  {
    //change password if new provided
    if ($newPassword)
      $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));

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

  public function getUsers()
  {
    return $this->em
      ->getRepository(User::class)
      ->findAllNotDeleted();
  }
}
