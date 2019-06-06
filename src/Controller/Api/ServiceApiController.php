<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Service;
use App\Entity\ServiceStatusHistory;
use App\Form\ServiceType;
use App\Service\Mail\Mailer\ServiceUpdatedMailer;

class ServiceApiController extends ApiController
{
  /**
   * @Route("/api/v1/services", name="getServices", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServices()
  {
    try
    {
      $services = $this->getDoctrine()
        ->getRepository(Service::class)
        ->findAllNotDeleted();

      return $this->respond($services);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/services/{guid}", name="getService", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getService($guid)
  {
    try
    {
      //get service
      $service = $this->getDoctrine()
        ->getRepository(Service::class)
        ->findByGuid($guid);

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
  public function createService(Request $request)
  {
    try
    {
      //create service object
      $service = new Service();

      //create form object for service
      $form = $this->createForm(ServiceType::class, $service, ['csrf_protection' => false]);

      //submit form
      $data = json_decode($request->getContent(), true);
      $form->submit($data);

      //return $this->respond($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $service = $form->getData();
        $em = $this->getDoctrine()->getManager();

        $serviceStatusHistory = new ServiceStatusHistory();
        $serviceStatusHistory->setService($service);
        $serviceStatusHistory->setStatus($service->getStatus());

        $em->persist($service);
        $em->persist($serviceStatusHistory);
        $em->flush();

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
   * @Route("/api/v1/services/{serviceGuid}", name="updateService", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateService(
    $serviceGuid,
    Request $request,
    ServiceUpdatedMailer $serviceUpdatedMailer
  )
  {
    try
    {
      //get service from database
      $service = $this->getDoctrine()
        ->getRepository(Service::class)
        ->findByGuid($serviceGuid);

      if (!$service)
        throw new \Exception('No service found');

      //get previous status
      $serviceStatus = $service->getStatus();

      //create form object for service
      $form = $this->createForm(ServiceType::class, $service, ['csrf_protection' => false]);

      //submit form
      $data = json_decode($request->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $service = $form->getData();
        $em = $this->getDoctrine()->getManager();

        //add new service status history if changed
        if ($serviceStatus != $service->getStatus())
        {
          $serviceStatusHistory = new ServiceStatusHistory();
          $serviceStatusHistory->setService($service);
          $serviceStatusHistory->setStatus($service->getStatus());
          $em->persist($serviceStatusHistory);

          //send update email
          $serviceUpdatedMailer->send($service);
        }

        $em->flush();

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
   * @Route("/api/v1/services/{guid}", name="deleteService", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteService($guid)
  {
    try
    {
      //get service
      $service = $this->getDoctrine()
        ->getRepository(Service::class)
        ->findByGuid($guid);

      //check for valid service
      if (!$service)
        return $this->respondWithErrors(['Invalid service']);

      //delete service
      $service->setDeletedOn(time());
      $service->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($service);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
