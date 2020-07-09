<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;
use App\Entity\Incident;
use App\Entity\ServiceStatusHistory;
use App\Service\Mail\Mailer\IncidentCreatedMailer;
use App\Service\Mail\Mailer\IncidentUpdatedMailer;

class IncidentFactory
{
  private $em;
  private $security;
  private $incidentCreatedMailer;
  private $incidentUpdatedMailer;

  public function __construct(
    EntityManagerInterface $em,
    Security $security,
    IncidentCreatedMailer $incidentCreatedMailer,
    IncidentUpdatedMailer $incidentUpdatedMailer
  )
  {
    $this->em = $em;
    $this->security = $security;
    $this->incidentCreatedMailer = $incidentCreatedMailer;
    $this->incidentUpdatedMailer = $incidentUpdatedMailer;
  }

  public function createIncident($incident)
  {
    //set user
    $incident->setCreatedBy($this->security->getUser());

    //set status and user of any updates
    foreach ($incident->getIncidentUpdates() as $update)
    {
       $update->setStatus($incident->getStatus());
       $update->setCreatedBy($this->security->getUser());
    }

    //update service statuses and store histories
    foreach ($incident->getIncidentServices() as $service)
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

    $this->em->persist($incident);
    $this->em->flush();

    //send notification emails
    $this->sendNotificationEmails('create', $incident);
  }

  public function updateIncident($incident, $originalServices, $originalUpdates)
  {
    //remove deleted services from database
    foreach ($originalServices as $service)
    {
      if ($incident->getIncidentServices()->contains($service) === false)
        $this->em->remove($service);
    }

    //remove deleted updates from database
    foreach ($originalUpdates as $update)
    {
      if ($incident->getIncidentUpdates()->contains($update) === false)
        $this->em->remove($update);
    }

    //set status and user of new updates
    foreach ($incident->getIncidentUpdates() as $update)
    {
      if ($originalUpdates->contains($update) === false)
      {
         $update->setStatus($incident->getStatus());
         $update->setCreatedBy($this->security->getUser());
      }
    }

    //update service statuses and store histories
    foreach ($incident->getIncidentServices() as $service)
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

    $this->em->flush();

    //send notification emails
    $this->sendNotificationEmails('update', $incident);
  }

  public function deleteIncident($incident)
  {
    //delete incident
    $incident->setDeletedOn(time());
    $incident->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getIncident($hashId)
  {
    //get incident
    return $this->em
      ->getRepository(Incident::class)
      ->findByHashId($hashId);
  }

  public function getIncidents($reverse = false, $maxRecords = null)
  {
    return $this->em
      ->getRepository(Incident::class)
      ->findAllNotDeleted($reverse, $maxRecords);
  }

  public function getPastIncidents($reverse = false, $maxRecords = null)
  {
    return $this->em
      ->getRepository(Incident::class)
      ->findAllPastIncidents($reverse, $maxRecords);
  }

  private function sendNotificationEmails($action, $incident)
  {
    //send email if services included
    if ($incident->getIncidentServices())
    {
      if ($action == 'create')
        $this->incidentCreatedMailer->send($incident);
      else if ($action == 'update')
        $this->incidentUpdatedMailer->send($incident);
    }
  }

  public static function getCurrentServices($incident)
  {
    $services = new ArrayCollection();

    foreach ($incident->getIncidentServices() as $service)
      $services->add($service);

    return $services;
  }

  public static function getCurrentUpdates($incident)
  {
    $updates = new ArrayCollection();

    foreach ($incident->getIncidentUpdates() as $update)
      $updates->add($update);

    return $updates;
  }
}
