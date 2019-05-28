<?php

namespace App\Service\Mail\Mailer;

use App\Service\Mail\MailManager;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;

class ServiceUpdatedMailer
{
  private $mailManager;
  private $em;

  public function __construct(MailManager $mailManager, EntityManagerInterface $em)
  {
    $this->mailManager = $mailManager;
    $this->em = $em;
  }

  public function send($service)
  {
    $subscribers = $this->getSubscribers($service);

    try
    {
      foreach ($subscribers as $subscriber)
      {
        $this->mailManager->sendEmail(
          'mail/serviceupdated.html.twig',
          [
            'service' => $service,
            'subscriber' => $subscriber
          ],
          'Service Updated',
          $subscriber->getEmail(),
          'statusbot@codeclouds.net',
          'Status Bot'
        );
      }
    }
    catch(Exception $ex)
    {
      //log error to db
    }
  }

  //return array of valid subscriptions
  private function getSubscribers($service)
  {
    $subscriptions = $this->em
      ->getRepository(Subscription::class)
      ->findAll();

    return array_filter(
      $subscriptions,
      function($subscription) use ($service)
      {
        $found = false;

        foreach ($subscription->getBlacklistedSubscriptionServices() as $blacklistedService) {
          if ($blacklistedService->getService() == $service) {
            $found = true;
            break;
          }
        }

        return !$found;
      }
    );
  }
}
