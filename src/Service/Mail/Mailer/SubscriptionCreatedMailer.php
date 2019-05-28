<?php

namespace App\Service\Mail\Mailer;

use App\Service\Mail\MailManager;
use App\Entity\Subscription;

class SubscriptionCreatedMailer
{
  private $mailManager;

  public function __construct(MailManager $mailManager)
  {
    $this->mailManager = $mailManager;
  }

  public function send($subscriber)
  {
    try
    {
      $this->mailManager->sendEmail(
        'mail/subscriptioncreated.html.twig',
        [
          'subscriber' => $subscriber
        ],
        'Subscription Created',
        $subscriber->getEmail(),
        'statusbot@codeclouds.net',
        'Status Bot'
      );
    }
    catch(Exception $ex)
    {
      //log error to db
    }
  }
}
