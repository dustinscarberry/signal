<?php

namespace App\Service\Manager;

use Doctrine\ORM\EntityManagerInterface;

class ServiceManager
{
  private $em;

  public function __construct(
    EntityManagerInterface $em
  )
  {
    $this->em = $em;
  }

  public function createService()
  {

  }

  public function updateService()
  {

  }

  public function deleteService()
  {

  }

  public function getService($hashId)
  {

  }

  public function getServices()
  {

  }
}
