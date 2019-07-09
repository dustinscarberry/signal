<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subscription;
use App\Service\Mail\Mailer\SubscriptionCreatedMailer;

class SubscriptionManager
{
  private $em;
  private $subscriptionCreatedMailer;

  public function __construct(
    EntityManagerInterface $em,
    SubscriptionCreatedMailer $subscriptionCreatedMailer
  )
  {
    $this->em = $em;
    $this->subscriptionCreatedMailer = $subscriptionCreatedMailer;
  }

  public function createSubscription($subscription)
  {
    $this->em->persist($subscription);
    $this->em->flush();

    //send subscription created email to user
    $this->sendNotificationEmails('create', $subscription);
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

  private function sendNotificationEmails($action, $subscription)
  {
    if ($action == 'create')
      $this->subscriptionCreatedMailer->send($subscription);
  }
}
