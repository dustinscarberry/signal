<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\ServiceStatus;
use App\Form\ServiceStatusType;
use App\Service\Manager\ServiceStatusManager;

class ServiceStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/servicestatuses", name="getServiceStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServiceStatuses(ServiceStatusManager $serviceStatusManager)
  {
    try
    {
      $serviceStatuses = $serviceStatusManager->getServiceStatuses();
      return $this->respond($serviceStatuses);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/servicestatuses/{hashId}", name="getServiceStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServiceStatus($hashId, ServiceStatusManager $serviceStatusManager)
  {
    try
    {
      //get service status
      $serviceStatus = $serviceStatusManager->getServiceStatus($hashId);

      //check for valid service status
      if (!$serviceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($serviceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/servicestatuses", name="createServiceStatus", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createServiceStatus(Request $req, ServiceStatusManager $serviceStatusManager)
  {
    try
    {
      $serviceStatus = new ServiceStatus();

      $form = $this->createForm(
        ServiceStatusType::class,
        $serviceStatus,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save new widget to database if valid
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceStatusManager->createServiceStatus($serviceStatus);

        return $this->respond($serviceStatus);
      }

      return $this->respondWithErrors(['Invalid Data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/servicestatuses/{hashId}", name="updateServiceStatus", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateServiceStatus($hashId, Request $req, ServiceStatusManager $serviceStatusManager)
  {
    try
    {
      //get status from database
      $status = $serviceStatusManager->getServiceStatus($hashId);

      if (!$status)
        throw new \Exception('Object not found');

      //create form object for status
      $form = $this->createForm(
        ServiceStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceStatusManager->updateServiceStatus();

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
   * @Route("/api/v1/servicestatuses/{hashId}", name="deleteServiceStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteServiceStatus($hashId, ServiceStatusManager $serviceStatusManager)
  {
    try
    {
      //get service status
      $serviceStatus = $serviceStatusManager->getServiceStatus($hashId);

      //check for valid service status
      if (!$serviceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete service status
      $serviceStatusManager->deleteServiceStatus($serviceStatus);
      
      //respond with object
      return $this->respond($serviceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
