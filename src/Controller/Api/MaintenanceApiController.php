<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Maintenance;

class MaintenanceApiController extends ApiController
{
  /**
   * @Route("/api/v1/maintenance", name="getMaintenances", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getMaintenances()
  {
    //get maintenance
    $maintenances = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findAllNotDeleted();

    //respond with object
    return $this->respond($maintenances);
  }

  /**
   * @Route("/api/v1/maintenance/{guid}", name="getMaintenance", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getMaintenance($guid)
  {
    //get maintenance
    $maintenance = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findByGuid($guid);

    //check for valid maintenance
    if (!$maintenance)
      return $this->respondWithErrors(['Invalid data']);

    //respond with object
    return $this->respond($maintenance);
  }

  /**
   * @Route("/api/v1/maintenance/{guid}", name="deleteMaintenance", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteMaintenance($guid)
  {
    //get maintenance
    $maintenance = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findByGuid($guid);

    //check for valid maintenance
    if (!$maintenance)
      return $this->respondWithErrors(['Invalid data']);

    //delete maintenance
    $maintenance->setDeletedOn(time());
    $maintenance->setDeletedBy($this->getUser());
    $this->getDoctrine()->getManager()->flush();

    //respond with object
    return $this->respond($maintenance);
  }
}
