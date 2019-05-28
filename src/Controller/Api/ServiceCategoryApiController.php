<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\ServiceCategory;

class ServiceCategoryApiController extends ApiController
{
  /**
  * @Route("/api/v1/servicecategories", name="getServiceCategories", methods={"GET"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getServiceCategories()
  {
    $serviceCategories = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findAllNotDeleted();

    return $this->respond($serviceCategories);
  }

  /**
  * @Route("/api/v1/servicecategories/{guid}", name="getServiceCategory", methods={"GET"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getServiceCategory($guid)
  {
    //get service category
    $serviceCategory = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findByGuid($guid);

    //check for valid service category
    if (!$serviceCategory)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($serviceCategory);
  }

  /**
  * @Route("/api/v1/servicecategories/{guid}", name="deleteServiceCategory", methods={"DELETE"})
  * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteServiceCategory($guid)
  {
    //get service category
    $serviceCategory = $this->getDoctrine()
      ->getRepository(ServiceCategory::class)
      ->findByGuid($guid);

    //check for valid service category
    if (!$serviceCategory)
      return $this->respondWithErrors(['Invalid data']);

    //delete service category
    $serviceCategory->setDeletedOn(time());
    $serviceCategory->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($serviceCategory);
  }
}
