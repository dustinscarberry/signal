<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Incident;

class IncidentApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidents", name="getIncidents", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidents()
  {
    $incidents = $this->getDoctrine()
      ->getRepository(Incident::class)
      ->findAllNotDeleted();

    return $this->respond($incidents);
  }

  /**
   * @Route("/api/v1/incidents/{guid}", name="getIncident", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncident($guid)
  {
    //get incident
    $incident = $this->getDoctrine()
      ->getRepository(Incident::class)
      ->findByGuid($guid);

    //check for valid incident
    if (!$incident)
      return $this->respondWithErrors(['Invalid data']);

    return $this->respond($incident);
  }

  /**
   * @Route("/api/v1/incidents/{guid}", name="deleteIncident", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteIncident($guid)
  {
    //get incident
    $incident = $this->getDoctrine()
      ->getRepository(Incident::class)
      ->findByGuid($guid);

    //check for valid incident
    if (!$incident)
      return $this->respondWithErrors(['Invalid data']);

    //soft delete incident
    $incident->setDeletedOn(time());
    $incident->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($incident);
  }
}
