<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\ServiceStatus;
use App\Form\ServiceStatusType;

class ServiceStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/servicestatuses", name="getServiceStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServiceStatuses()
  {
    $serviceStatuses = $this->getDoctrine()
      ->getRepository(ServiceStatus::class)
      ->findAllNotDeleted();

    return $this->respond($serviceStatuses);
  }

  /**
   * @Route("/api/v1/servicestatuses/{guid}", name="getServiceStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServiceStatus($guid)
  {
    //get service status
    $serviceStatus = $this->getDoctrine()
      ->getRepository(ServiceStatus::class)
      ->findByGuid($guid);

    //check for valid service status
    if (!$serviceStatus)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($serviceStatus);
  }

  /**
   * @Route("/api/v1/servicestatuses", name="createServiceStatus", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createServiceStatus()
  {
    $serviceStatus = new ServiceStatus();
    $form = $this->createForm(ServiceStatusType::class, $serviceStatus);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);

    //save new widget to database if valid
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceStatus = $form->getData();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($serviceStatus);
      $entityManager->flush();

      return $this->respond($serviceStatus);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }

  /**
   * @Route("/api/v1/servicestatuses/{guid}", name="deleteServiceStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteServiceStatus($guid)
  {
    //get service status
    $serviceStatus = $this->getDoctrine()
      ->getRepository(ServiceStatus::class)
      ->findByGuid($guid);

    //check for valid service status
    if (!$serviceStatus)
      return $this->respondWithErrors(['Invalid data']);

    //delete service status
    $serviceStatus->setDeletedOn(time());
    $serviceStatus->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($serviceStatus);
  }
}
