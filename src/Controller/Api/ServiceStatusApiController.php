<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\ServiceStatus;
use App\Form\ServiceStatusType;

class ServiceStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/servicestatuses", name="getServiceStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getServiceStatuses()
  {
    try
    {
      $serviceStatuses = $this->getDoctrine()
        ->getRepository(ServiceStatus::class)
        ->findAllNotDeleted();

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
  public function getServiceStatus($hashId)
  {
    try
    {
      //get service status
      $serviceStatus = $this->getDoctrine()
        ->getRepository(ServiceStatus::class)
        ->findByHashId($hashId);

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
  public function createServiceStatus(Request $req)
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
        $serviceStatus = $form->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($serviceStatus);
        $entityManager->flush();

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
  public function updateServiceStatus($hashId, Request $req)
  {
    try
    {
      //get status from database
      $status = $this->getDoctrine()
        ->getRepository(ServiceStatus::class)
        ->findByHashId($hashId);

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
        $status = $form->getData();
        $this->getDoctrine()->getManager()->flush();

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
  public function deleteServiceStatus($hashId)
  {
    try
    {
      //get service status
      $serviceStatus = $this->getDoctrine()
        ->getRepository(ServiceStatus::class)
        ->findByHashId($hashId);

      //check for valid service status
      if (!$serviceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete service status
      $serviceStatus->setDeletedOn(time());
      $serviceStatus->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($serviceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
