<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Service\Factory\MaintenanceFactory;

class MaintenanceApiController extends ApiController
{
  #[Route('/api/v1/maintenance', name: 'getMaintenances', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getMaintenances(MaintenanceFactory $maintenanceFactory)
  {
    try
    {
      //get maintenance
      $maintenances = $maintenanceFactory->getMaintenances();
      return $this->respond($maintenances);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/maintenance/{hashId}', name: 'getMaintenance', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getMaintenance($hashId, MaintenanceFactory $maintenanceFactory)
  {
    try
    {
      //get maintenance
      $maintenance = $maintenanceFactory->getMaintenance($hashId);

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

  #[Route('/api/v1/maintenance', name: 'createMaintenance', methods: ['POST'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function createMaintenance(Request $req, MaintenanceFactory $maintenanceFactory)
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

        $maintenanceFactory->createMaintenance(
          $maintenance,
          $form->get('updateServiceStatuses')->getData()
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

  #[Route('/api/v1/maintenance/{hashId}', name: 'updateMaintenance', methods: ['PATCH'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function updateMaintenance($hashId, Request $req, MaintenanceFactory $maintenanceFactory)
  {
    try
    {
      //get maintenance from database
      $maintenance = $maintenanceFactory->getMaintenance($hashId);

      if (!$maintenance)
        throw new \Exception('Item not found');

      //get original updates and services to compare against
      $originalServices = MaintenanceFactory::getCurrentServices($maintenance);
      $originalUpdates = MaintenanceFactory::getCurrentUpdates($maintenance);

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
        $maintenanceFactory->updateMaintenance(
          $maintenance,
          $form->get('updateServiceStatuses')->getData(),
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

  #[Route('/api/v1/maintenance/{hashId}', name: 'deleteMaintenance', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteMaintenance($hashId, MaintenanceFactory $maintenanceFactory)
  {
    try
    {
      //get maintenance
      $maintenance = $maintenanceFactory->getMaintenance($hashId);

      //check for valid maintenance
      if (!$maintenance)
        return $this->respondWithErrors(['Invalid data']);

      //delete maintenance
      $maintenanceFactory->deleteMaintenance($maintenance);

      //respond with object
      return $this->respond($maintenance);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
