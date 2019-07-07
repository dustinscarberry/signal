<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Service\Manager\MaintenanceManager;

class MaintenanceApiController extends ApiController
{
  /**
   * @Route("/api/v1/maintenance", name="getMaintenances", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getMaintenances(MaintenanceManager $maintenanceManager)
  {
    try
    {
      //get maintenance
      $maintenances = $maintenanceManager->getMaintenances();
      return $this->respond($maintenances);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance/{hashId}", name="getMaintenance", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getMaintenance($hashId, MaintenanceManager $maintenanceManager)
  {
    try
    {
      //get maintenance
      $maintenance = $maintenanceManager->getMaintenance($hashId);

      //check for valid maintenance
      if (!$maintenance)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($maintenance);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance", name="createMaintenance", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function createMaintenance(Request $req, MaintenanceManager $maintenanceManager)
  {
    try
    {
      //create maintenance object
      $maintenance = new Maintenance();

      //create form object for maintenance
      $form = $this->createForm(
        MaintenanceType::class,
        $maintenance,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {

        $maintenanceManager->createMaintenance(
          $maintenance,
          $form->get('updateServiceStatuses')
        );

        return $this->respond($maintenance);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance/{hashId}", name="updateMaintenance", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function updateMaintenance($hashId, Request $req, MaintenanceManager $maintenanceManager)
  {
    try
    {
      //get maintenance from database
      $maintenance = $maintenanceManager->getMaintenance($hashId);

      if (!$maintenance)
        throw new \Exception('Item not found');

      //get original updates and services to compare against
      $originalServices = MaintenanceManager::getCurrentServices($maintenance);
      $originalUpdates = MaintenanceManager::getCurrentUpdates($maintenance);

      //create form object for maintenance
      $form = $this->createForm(
        MaintenanceType::class,
        $maintenance,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $maintenanceManager->updateMaintenance(
          $maintenance,
          $form->get('updateServiceStatuses'),
          $originalServices,
          $originalUpdates
        );

        return $this->respond($maintenance);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance/{hashId}", name="deleteMaintenance", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteMaintenance($hashId, MaintenanceManager $maintenanceManager)
  {
    try
    {
      //get maintenance
      $maintenance = $maintenanceManager->getMaintenance($hashId);

      //check for valid maintenance
      if (!$maintenance)
        return $this->respondWithErrors(['Invalid data']);

      //delete maintenance
      $maintenanceManager->deleteMaintenance($maintenance);

      //respond with object
      return $this->respond($maintenance);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
