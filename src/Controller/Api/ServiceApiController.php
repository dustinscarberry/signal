<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Service\Factory\ServiceFactory;

class ServiceApiController extends ApiController
{
  /**
   * @Route("/api/v1/services", name="getServices", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServices(ServiceFactory $serviceFactory)
  {
    try
    {
      $services = $serviceFactory->getServices();
      return $this->respond($services);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/services/{hashId}", name="getService", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getService($hashId, ServiceFactory $serviceFactory)
  {
    try
    {
      //get service
      $service = $serviceFactory->getService($hashId);

      //check for valid service
      if (!$service)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($service);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/services", name="createService", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createService(Request $req, ServiceFactory $serviceFactory)
  {
    try
    {
      //create service object
      $service = new Service();

      //create form object for service
      $form = $this->createForm(ServiceType::class, $service, ['csrf_protection' => false]);

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceFactory->createService($service);

        //respond with object
        return $this->respond($service);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/services/{hashId}", name="updateService", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateService($hashId, Request $req, ServiceFactory $serviceFactory)
  {
    try
    {
      //get service from database
      $service = $serviceFactory->getService($hashId);

      if (!$service)
        throw new \Exception('No service found');

      //get previous status
      $currentServiceStatus = $serviceFactory->getCurrentServiceStatus($service);

      //create form object for service
      $form = $this->createForm(ServiceType::class, $service, ['csrf_protection' => false]);

      //submit form
      $data = json_decode($request->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $serviceFactory->updateService($service, $currentServiceStatus);

        //respond with object
        return $this->respond($service);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/services/{hashId}", name="deleteService", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteService($hashId, ServiceFactory $serviceFactory)
  {
    try
    {
      //get service
      $service = $serviceFactory->getService($hashId);

      //check for valid service
      if (!$service)
        return $this->respondWithErrors(['Invalid service']);

      //delete service
      $serviceFactory->deleteService($service);

      //respond with object
      return $this->respond($service);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
