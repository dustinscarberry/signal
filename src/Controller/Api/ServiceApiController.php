<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Service;

class ServiceApiController extends ApiController
{
  /**
   * @Route("/api/v1/services", name="getServices", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServices()
  {
    $services = $this->getDoctrine()
      ->getRepository(Service::class)
      ->findAllNotDeleted();

    return $this->respond($services);
  }

  /**
   * @Route("/api/v1/services/{guid}", name="getService", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getService($guid)
  {
    //get service
    $service = $this->getDoctrine()
      ->getRepository(Service::class)
      ->findByGuid($guid);

    //check for valid service
    if (!$service)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($service);
  }

  /**
   * @Route("/api/v1/services/{guid}", name="deleteService", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteService($guid)
  {
    //get service
    $service = $this->getDoctrine()
      ->getRepository(Service::class)
      ->findByGuid($guid);

    //check for valid service
    if (!$service)
      return $this->respondWithErrors(['Invalid data']);

    //delete service
    $service->setDeletedOn(time());
    $service->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($service);
  }
}
