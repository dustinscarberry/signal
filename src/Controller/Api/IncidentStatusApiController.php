<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\IncidentStatus;

class IncidentStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidentstatuses", name="getIncidentStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatuses()
  {
    //get incident statuses
    $incidentStatuses = $this->getDoctrine()
      ->getRepository(IncidentStatus::class)
      ->findAllNotDeleted();

    //respond with object
    return $this->respond($incidentStatuses);
  }

  /**
   * @Route("/api/v1/incidentstatuses/{guid}", name="getIncidentStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatus($guid)
  {
    //get incident status
    $incidentStatus = $this->getDoctrine()
      ->getRepository(IncidentStatus::class)
      ->findByGuid($guid);

    //check for valid incident status
    if (!$incidentStatus)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($incidentStatus);
  }

  /**
   * @Route("/api/v1/incidentstatuses/{guid}", name="deleteIncidentStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteIncidentStatus($guid)
  {
    //get incident status
    $incidentStatus = $this->getDoctrine()
      ->getRepository(IncidentStatus::class)
      ->findByGuid($guid);

    //check for valid incident status
    if (!$incidentStatus)
      return $this->respondWithErrors(['Invalid data']);

    //delete incident status
    $incidentStatus->setDeletedOn(time());
    $incidentStatus->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($incidentStatus);
  }
}
