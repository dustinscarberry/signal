<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subscription;

class SubscriptionManager
{
  private $em;

  public function __construct(
    EntityManagerInterface $em
  )
  {
    $this->em = $em;
  }

  public function deleteSubscription($subscription)
  {
    $this->em->remove($subscription);
    $this->em->flush();
  }

  public function getSubscription($hashId)
  {
    return $this->em
      ->getRepository(Subscription::class)
      ->findByHashId($hashId);
  }

  public function getSubscriptions()
  {
    return $this->em
      ->getRepository(Subscription::class)
      ->findAll();
  }
}
