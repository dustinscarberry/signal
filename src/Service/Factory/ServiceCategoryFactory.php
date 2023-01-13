<?php

namespace App\Service\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\ServiceCategory;

class ServiceCategoryFactory
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

  public function createServiceCategory($serviceCategory)
  {
    //just persist entity and flush :)
    $this->em->persist($serviceCategory);
    $this->em->flush();
  }

  public function updateServiceCategory()
  {
    //just flush entity already mapped to form :)
    $this->em->flush();
  }

  public function deleteServiceCategory($serviceCategory)
  {
    $serviceCategory->setDeletedOn(time());
    $serviceCategory->setDeletedBy($this->security->getUser());
    $this->em->flush();
  }

  public function getServiceCategory($hashId)
  {
    return $this->em
      ->getRepository(ServiceCategory::class)
      ->findByHashId($hashId);
  }

  public function getServiceCategories()
  {
    return $this->em
      ->getRepository(ServiceCategory::class)
      ->findAllNotDeleted();
  }
}
