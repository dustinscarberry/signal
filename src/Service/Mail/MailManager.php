<?php

namespace App\Service\Mail;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Templating\EngineInterface;
use App\Model\AppConfig;
use Doctrine\ORM\EntityManagerInterface;

class MailManager
{
  private $twig;
  private $mailer;
  private $appConfig;

  public function __construct(EngineInterface $twig, AppConfig $appConfig, Swift_Mailer $mailer)
  {
    $this->twig = $twig;
    $this->appConfig = $appConfig;
    $this->mailer = $mailer;
  }

  public function sendEmail(string $template, $parameters, string $subject, string $to)
  {
    //merge passed parameters with defaults
    $siteUrl = rtrim($appConfig->getSiteUrl(), '/');
    $parameters = array_merge($parameters, ['siteUrl' => $siteUrl]);

    //compose email
    $message = (new Swift_Message($subject))
      ->setFrom([$this->appConfig->getMailFromAddress() => $this->appConfig->getMailFromName()])
      ->setTo($to)
      ->setBody(
        $this->twig->render(
          $template,
          $parameters
        ),
        'text/html'
      );

    //send email
    $this->mailer->send($message);
  }
}
