<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CustomMetricDatapoint;

class CustomMetricDatapointFactory
{
  private $em;

  public function __construct(
    EntityManagerInterface $em
  )
  {
    $this->em = $em;
  }

  public function createCustomMetricDatapoint($customMetricDatapoint)
  {
    $this->em->persist($customMetricDatapoint);
    $this->em->flush();
  }

  public function deleteCustomMetricDatapoint($customMetricDatapoint)
  {
    $this->em->remove($customMetricDatapoint);
    $this->em->flush();
  }

  public function getCustomMetricDatapoint($hashId)
  {
    return $this->em
      ->getRepository(CustomMetricDatapoint::class)
      ->findByHashId($hashId);
  }
}
