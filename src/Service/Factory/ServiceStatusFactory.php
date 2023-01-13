<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\ServiceStatus;

class ServiceStatusFactory
{
  private $em;
  private $security;

  public function __construct(
    EntityManagerInterface $em,
    Security $security
  )
  {
    $this->em = $em;
    $this->security = $security;
  }

  public function createServiceStatus($serviceStatus)
  {
    $this->em->persist($serviceStatus);
    $this->em->flush();
  }

  public function updateServiceStatus()
  {
    $this->em->flush();
  }

  public function deleteServiceStatus($serviceStatus)
  {
    $serviceStatus->setDeletedOn(time());
    $serviceStatus->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getServiceStatus($hashId)
  {
    return $this->em
      ->getRepository(ServiceStatus::class)
      ->findByHashId($hashId);
  }

  public function getServiceStatuses()
  {
    return $this->em
      ->getRepository(ServiceStatus::class)
      ->findAllNotDeleted();
  }
}
