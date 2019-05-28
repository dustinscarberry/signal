<?php

namespace App\Service\Mail\Mailer;

use App\Service\Mail\MailManager;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;

class IncidentCreatedMailer
{
  private $mailManager;
  private $em;

  public function __construct(MailManager $mailManager, EntityManagerInterface $em)
  {
    $this->mailManager = $mailManager;
    $this->em = $em;
  }

  public function send($incident)
  {
    $subscribers = $this->getSubscribers($incident->getIncidentServices());

    try
    {
      foreach ($subscribers as $subscriber)
      {
        $this->mailManager->sendEmail(
          'mail/incidentcreated.html.twig',
          [
            'incident' => $incident,
            'subscriber' => $subscriber
          ],
          'Incident Created - ' . $incident->getName(),
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
