<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\MaintenanceStatus;

class MaintenanceStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/maintenancestatuses", name="getMaintenanceStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getMaintenanceStatuses()
  {
    //get maintenance statuses
    $maintenanceStatuses = $this->getDoctrine()
      ->getRepository(MaintenanceStatus::class)
      ->findAllNotDeleted();

    //respond with object
    return $this->respond($maintenanceStatuses);
  }

  /**
   * @Route("/api/v1/maintenancestatuses/{guid}", name="getMaintenanceStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getMaintenanceStatus($guid)
  {
    //get maintenance status
    $maintenanceStatus = $this->getDoctrine()
      ->getRepository(MaintenanceStatus::class)
      ->findByGuid($guid);

    //check for valid maintenance status
    if (!$maintenanceStatus)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($maintenanceStatus);
  }

  /**
   * @Route("/api/v1/maintenancestatuses/{guid}", name="deleteMaintenanceStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteMaintenanceStatus($guid)
  {
    //get maintenance status
    $maintenanceStatus = $this->getDoctrine()
      ->getRepository(MaintenanceStatus::class)
      ->findByGuid($guid);

    //check for valid maintenance status
    if (!$maintenanceStatus)
      return $this->respondWithErrors(['Invalid data']);

    //delete maintenance status
    $maintenanceStatus->setDeletedOn(time());
    $maintenanceStatus->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($maintenanceStatus);
  }
}
