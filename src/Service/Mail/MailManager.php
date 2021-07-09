<?php

namespace App\Service\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Model\AppConfig;
use Doctrine\ORM\EntityManagerInterface;

class MailManager
{
  private $mailer;
  private $appConfig;

  public function __construct(AppConfig $appConfig, MailerInterface $mailer)
  {
    $this->appConfig = $appConfig;
    $this->mailer = $mailer;
  }

  public function sendEmail(string $template, $parameters, string $subject, string $to)
  {
    // compose email
    $email = (new TemplatedEmail())
      ->from(Address::create($this->appConfig->getMailFromName() . ' <' . $this->appConfig->getMailFromAddress() . '>'))
      ->to($to)
      ->subject($subject)
      ->htmlTemplate($template)
      ->context($parameters);

    // send email
    $this->mailer->send($email);
  }
}
