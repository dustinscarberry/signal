<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\MaintenanceStatus;
use App\Form\MaintenanceStatusType;

class MaintenanceStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/maintenancestatuses", name="getMaintenanceStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getMaintenanceStatuses()
  {
    try
    {
      //get maintenance statuses
      $maintenanceStatuses = $this->getDoctrine()
        ->getRepository(MaintenanceStatus::class)
        ->findAllNotDeleted();

      //respond with object
      return $this->respond($maintenanceStatuses);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenancestatuses/{guid}", name="getMaintenanceStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getMaintenanceStatus($guid)
  {
    try
    {
      //get maintenance status
      $maintenanceStatus = $this->getDoctrine()
        ->getRepository(MaintenanceStatus::class)
        ->findByGuid($guid);

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
  public function createMaintenanceStatus(Request $req)
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
        $status = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($status);
        $em->flush();

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
   * @Route("/api/v1/maintenancestatuses/{guid}", name="updateMaintenanceStatus", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateMaintenanceStatus($guid, Request $req)
  {
    try
    {
      //get status from database
      $status = $this->getDoctrine()
        ->getRepository(MaintenanceStatus::class)
        ->findByGuid($guid);

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
        $status = $form->getData();
        $this->getDoctrine()->getManager()->flush();

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
   * @Route("/api/v1/maintenancestatuses/{guid}", name="deleteMaintenanceStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteMaintenanceStatus($guid)
  {
    try
    {
      //get maintenance status
      $maintenanceStatus = $this->getDoctrine()
        ->getRepository(MaintenanceStatus::class)
        ->findByGuid($guid);

      //check for valid maintenance status
      if (!$maintenanceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete maintenance status
      $maintenanceStatus->setDeletedOn(time());
      $maintenanceStatus->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($maintenanceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
