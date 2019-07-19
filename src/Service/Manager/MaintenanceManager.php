<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;
use App\Service\Mail\Mailer\MaintenanceCreatedMailer;
use App\Service\Mail\Mailer\MaintenanceUpdatedMailer;
use App\Service\Generator\ExchangeEventGenerator;
use App\Entity\Maintenance;
use App\Entity\ServiceStatusHistory;
use App\Entity\ExchangeCalendarEvent;
use App\Entity\GoogleCalendarEvent;
use App\Model\AppConfig;
use Psr\Log\LoggerInterface;

class MaintenanceManager
{
  private $em;
  private $security;
  private $appConfig;
  private $maintenanceCreatedMailer;
  private $maintenanceUpdatedMailer;
  private $logger;

  public function __construct(
    EntityManagerInterface $em,
    Security $security,
    AppConfig $appConfig,
    MaintenanceCreatedMailer $maintenanceCreatedMailer,
    MaintenanceUpdatedMailer $maintenanceUpdatedMailer,
    LoggerInterface $logger
  )
  {
    $this->em = $em;
    $this->security = $security;
    $this->appConfig = $appConfig;
    $this->maintenanceCreatedMailer = $maintenanceCreatedMailer;
    $this->maintenanceUpdatedMailer = $maintenanceUpdatedMailer;
    $this->logger = $logger;
  }

  public function createMaintenance($maintenance, $updateServiceStatuses)
  {
    //set created by
    $maintenance->setCreatedBy($this->security->getUser());

    //set status and user of any updates
    foreach ($maintenance->getMaintenanceUpdates() as $update)
    {
      $update->setStatus($maintenance->getStatus());
      $update->setCreatedBy($this->security->getUser());
    }

    //check for status update check
    if ($updateServiceStatuses)
    {
      //update maintenance statuses and store histories
      foreach ($maintenance->getMaintenanceServices() as $service)
      {
        if ($service->getStatus() != $service->getService()->getStatus())
        {
          $service->getService()->setStatus($service->getStatus());

          $serviceStatusHistory = new ServiceStatusHistory();
          $serviceStatusHistory->setService($service->getService());
          $serviceStatusHistory->setStatus($service->getStatus());
          $this->em->persist($serviceStatusHistory);
        }
      }
    }

    $this->em->persist($maintenance);
    $this->em->flush();

    //send notification emails
    $this->sendNotificationEmails('create', $maintenance);

    //sync to exchange calendar
    $this->syncToExchangeCalendar($maintenance);

    //sync to google calendar
    $this->syncToGoogleCalendar($maintenance);
  }

  public function updateMaintenance($maintenance, $updateServiceStatuses, $originalServices, $originalUpdates)
  {
    //remove deleted services from database
    foreach ($originalServices as $service)
    {
      if ($maintenance->getMaintenanceServices()->contains($service) === false)
        $this->em->remove($service);
    }

    //remove deleted updates from database
    foreach ($originalUpdates as $update)
    {
      if ($maintenance->getMaintenanceUpdates()->contains($update) === false)
        $this->em->remove($update);
    }

    //set status and user of new updates
    foreach ($maintenance->getMaintenanceUpdates() as $update)
    {
      if ($originalUpdates->contains($update) === false)
      {
        $update->setStatus($maintenance->getStatus());
        $update->setCreatedBy($this->security->getUser());
      }
    }

    //check for status update check
    if ($updateServiceStatuses)
    {
      //update maintenance statuses and store histories
      foreach ($maintenance->getMaintenanceServices() as $service)
      {
        if ($service->getStatus() != $service->getService()->getStatus())
        {
          $service->getService()->setStatus($service->getStatus());

          $serviceStatusHistory = new ServiceStatusHistory();
          $serviceStatusHistory->setService($service->getService());
          $serviceStatusHistory->setStatus($service->getStatus());
          $this->em->persist($serviceStatusHistory);
        }
      }
    }

    $this->em->flush();

    //send notification emails
    $this->sendNotificationEmails('update', $maintenance);

    //sync to exchange calendar
    $this->syncToExchangeCalendar($maintenance);

    //sync to google calendar
    $this->syncToGoogleCalendar($maintenance);
  }

  public function deleteMaintenance($maintenance)
  {
    $this->syncToExchangeCalendar($maintenance, true);

    //delete maintenance
    $maintenance->setDeletedOn(time());
    $maintenance->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getMaintenance($hashId)
  {
    //get maintenance
    return $this->em
      ->getRepository(Maintenance::class)
      ->findByHashId($hashId);
  }

  public function getMaintenances()
  {
    //get maintenance
    return $this->em
      ->getRepository(Maintenance::class)
      ->findAllNotDeleted();
  }

  public function getPastMaintenances()
  {
    return $this->em
      ->getRepository(Maintenance::class)
      ->findAllPastMaintenance();
  }

  public function getScheduledMaintenances()
  {
    return $this->em
      ->getRepository(Maintenance::class)
      ->findAllScheduledMaintenance();
  }

  private function sendNotificationEmails($action, $maintenance)
  {
    //send email if services included
    if ($maintenance->getMaintenanceServices())
    {
      if ($action == 'create')
        $this->maintenanceCreatedMailer->send($maintenance);
      else if ($action == 'update')
        $this->maintenanceUpdatedMailer->send($maintenance);
    }
  }

  private function syncToExchangeCalendar($maintenance, $remove = false)
  {
    if ($this->appConfig->getEnableExchangeCalendarSync())
    {
      if ($_ENV['EXCHANGE_CALENDAR_HOST'] != ''
        && $_ENV['EXCHANGE_CALENDAR_USERNAME'] != ''
        && $_ENV['EXCHANGE_CALENDAR_PASSWORD'] != ''
        && $_ENV['EXCHANGE_CALENDAR_VERSION'] != ''
      )
      {
        $exchangeEventId = $maintenance->getExchangeCalendarEventId();

        try
        {
          if ($exchangeEventId)
          {
            if ($remove)
              ExchangeEventGenerator::deleteEvent($exchangeEventId);
            else
              ExchangeEventGenerator::updateEvent($maintenance, $exchangeEventId);
          }

          else
          {
            $eventId = ExchangeEventGenerator::createEvent($maintenance);

            //save to database
            $maintenance->setExchangeCalendarEventId($eventId);
            $this->em->flush();
          }
        }
        catch (\Exception $e)
        {
          $logger->error('Exchange Calendar Sync issue', $e);
        }
      }
      else
        $logger->error('Exchange Calendar Sync enabled, however .env.local credentials are invalid');
    }
  }

  private function syncToGoogleCalendar($maintenance)
  {

  }

  public static function getCurrentServices($maintenance)
  {
    $services = new ArrayCollection();

    foreach ($maintenance->getMaintenanceServices() as $service)
      $services->add($service);

    return $services;
  }

  public static function getCurrentUpdates($maintenance)
  {
    $updates = new ArrayCollection();

    foreach ($maintenance->getMaintenanceUpdates() as $update)
      $updates->add($update);

    return $updates;
  }
}
