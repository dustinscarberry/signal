<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CustomMetric;

class CustomMetricFactory
{
  private $em;

  public function __construct(
    EntityManagerInterface $em
  )
  {
    $this->em = $em;
  }

  public function createCustomMetric($customMetric)
  {
    $this->em->persist($customMetric);
    $this->em->flush();
  }

  public function updateCustomMetric()
  {
    $this->em->flush();
  }

  public function deleteCustomMetric($customMetric)
  {
    $this->em->remove($customMetric);
    $this->em->flush();
  }

  public function getCustomMetric($hashId)
  {
    return $this->em
      ->getRepository(CustomMetric::class)
      ->findByHashId($hashId);
  }

  public function getCustomMetrics()
  {
    return $this->em
      ->getRepository(CustomMetric::class)
      ->findAll();
  }
}
