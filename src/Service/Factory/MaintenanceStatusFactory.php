<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\MaintenanceStatus;

class MaintenanceStatusFactory
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

  public function createMaintenanceStatus($maintenanceStatus)
  {
    $this->em->persist($maintenanceStatus);
    $this->em->flush();
  }

  public function updateMaintenanceStatus()
  {
    $this->em->flush();
  }

  public function deleteMaintenanceStatus($maintenanceStatus)
  {
    //delete maintenance status
    $maintenanceStatus->setDeletedOn(time());
    $maintenanceStatus->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getMaintenanceStatus($hashId)
  {
    return $this->em
      ->getRepository(MaintenanceStatus::class)
      ->findByHashId($hashId);
  }

  public function getMaintenanceStatuses()
  {
    return $this->em
      ->getRepository(MaintenanceStatus::class)
      ->findAllNotDeleted();
  }
}
