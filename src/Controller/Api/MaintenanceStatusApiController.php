<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\MaintenanceStatus;
use App\Form\MaintenanceStatusType;
use App\Service\Manager\MaintenanceStatusManager;

class MaintenanceStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/maintenancestatuses", name="getMaintenanceStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getMaintenanceStatuses(MaintenanceStatusManager $maintenanceStatusManager)
  {
    try
    {
      //get maintenance statuses
      $maintenanceStatuses = $maintenanceStatusManager->getMaintenanceStatuses();
      return $this->respond($maintenanceStatuses);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenancestatuses/{hashId}", name="getMaintenanceStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getMaintenanceStatus($hashId, MaintenanceStatusManager $maintenanceStatusManager)
  {
    try
    {
      //get maintenance status
      $maintenanceStatus = $maintenanceStatusManager->getMaintenanceStatus($hashId);

      //check for valid maintenance status
      if (!$maintenanceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($maintenanceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenancestatuses", name="createMaintenanceStatus", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createMaintenanceStatus(Request $req, MaintenanceStatusManager $maintenanceStatusManager)
  {
    try
    {
      //create status object
      $status = new MaintenanceStatus();

      //create form object for status
      $form = $this->createForm(
        MaintenanceStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $maintenanceStatusManager->createMaintenanceStatus($status);

        //respond with object
        return $this->respond($status);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenancestatuses/{hashId}", name="updateMaintenanceStatus", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateMaintenanceStatus($hashId, Request $req, MaintenanceStatusManager $maintenanceStatusManager)
  {
    try
    {
      //get status from database
      $status = $maintenanceStatusManager->getMaintenanceStatus($hashId);

      if (!$status)
        return $this->respondWithErrors(['Invalid data']);

      //create form object for status
      $form = $this->createForm(
        MaintenanceStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $maintenanceStatusManager->updateMaintenanceStatus();

        //respond with object
        return $this->respond($status);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenancestatuses/{hashId}", name="deleteMaintenanceStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteMaintenanceStatus($hashId, MaintenanceStatusManager $maintenanceStatusManager)
  {
    try
    {
      //get maintenance status
      $maintenanceStatus = $maintenanceStatusManager->getMaintenanceStatus($hashId);

      //check for valid maintenance status
      if (!$maintenanceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete maintenance status
      $maintenanceStatusManager->deleteMaintenanceStatus($maintenanceStatus);

      //respond with object
      return $this->respond($maintenanceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
