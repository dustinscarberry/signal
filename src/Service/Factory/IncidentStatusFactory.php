<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\IncidentStatus;

class IncidentStatusFactory
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

  public function createIncidentStatus($incidentStatus)
  {
    $this->em->persist($incidentStatus);
    $this->em->flush();
  }

  public function updateIncidentStatus()
  {
    $this->em->flush();
  }

  public function deleteIncidentStatus($incidentStatus)
  {
    $incidentStatus->setDeletedOn(time());
    $incidentStatus->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getIncidentStatus($hashId)
  {
    return $this->em
      ->getRepository(IncidentStatus::class)
      ->findByHashId($hashId);
  }

  public function getIncidentStatuses()
  {
    return $this->em
      ->getRepository(IncidentStatus::class)
      ->findAllNotDeleted();
  }
}
