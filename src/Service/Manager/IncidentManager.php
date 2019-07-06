<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class IncidentManager
{
  private $em;

  public function __construct(
    EntityManagerInterface $em
  )
  {
    $this->em = $em;
  }

  public function createIncident()
  {

  }

  public function updateIncident()
  {

  }

  public function deleteIncident()
  {
    
  }






}
