<?php

namespace App\Service\Mail\Mailer;

use App\Service\Mail\MailManager;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;

class MaintenanceCreatedMailer
{
  private $mailManager;
  private $em;

  public function __construct(MailManager $mailManager, EntityManagerInterface $em)
  {
    $this->mailManager = $mailManager;
    $this->em = $em;
  }

  public function send($maintenance)
  {
    $subscribers = $this->getSubscribers($maintenance->getMaintenanceServices());

    try
    {
      foreach ($subscribers as $subscriber)
      {
        $this->mailManager->sendEmail(
          'mail/maintenancecreated.html.twig',
          [
            'maintenance' => $maintenance,
            'subscriber' => $subscriber
          ],
          'Maintenance Created - ' . $maintenance->getName(),
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
  private function getSubscribers($services)
  {
    $subscriptions = $this->em
      ->getRepository(Subscription::class)
      ->findAll();

    return array_filter(
      $subscriptions,
      function($subscription) use ($services)
      {
        $candidate = false;

        foreach ($services as $service)
        {
          if (!$subscription->getBlacklistedSubscriptionServices()->contains($service))
          {
            $candidate = true;
            break;
          }
        }

        return $candidate;
      }
    );
  }
}
