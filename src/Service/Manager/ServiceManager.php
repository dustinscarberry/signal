<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Service\Mail\Mailer\ServiceUpdatedMailer;
use App\Entity\Service;
use App\Entity\ServiceStatusHistory;

class ServiceManager
{
  private $em;
  private $security;
  private $serviceUpdatedMailer;

  public function __construct(
    EntityManagerInterface $em,
    Security $security,
    ServiceUpdatedMailer $serviceUpdatedMailer
  )
  {
    $this->em = $em;
    $this->security = $security;
    $this->serviceUpdatedMailer = $serviceUpdatedMailer;
  }

  public function createService(Service $service)
  {
    $serviceStatusHistory = new ServiceStatusHistory();
    $serviceStatusHistory->setService($service);
    $serviceStatusHistory->setStatus($service->getStatus());

    $this->em->persist($service);
    $this->em->persist($serviceStatusHistory);
    $this->em->flush();
  }

  public function updateService(Service $service, $currentServiceStatus)
  {
    //add new service status history if changed
    if ($currentServiceStatus != $service->getStatus())
    {
      $serviceStatusHistory = new ServiceStatusHistory();
      $serviceStatusHistory->setService($service);
      $serviceStatusHistory->setStatus($service->getStatus());
      $this->em->persist($serviceStatusHistory);

      //send update email
      $this->sendNotificationEmails('update', $service);
    }

    $this->em->flush();
  }

  public function deleteService($service)
  {
    //delete service
    $service->setDeletedOn(time());
    $service->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getService($hashId)
  {
    //get service from database
    return $this->em
      ->getRepository(Service::class)
      ->findByHashId($hashId);
  }

  public function getServices()
  {
    return $this->em
      ->getRepository(Service::class)
      ->findAllNotDeleted();
  }

  public function getCurrentServiceStatus($service)
  {
    return $service->getStatus();
  }

  private function sendNotificationEmails($action, $service)
  {
    if ($action == 'update')
      $this->serviceUpdatedMailer->send($service);
  }
}
