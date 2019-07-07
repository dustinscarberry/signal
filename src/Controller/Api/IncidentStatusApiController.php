<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\IncidentStatus;
use App\Form\IncidentStatusType;
use App\Service\Manager\IncidentStatusManager;

class IncidentStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidentstatuses", name="getIncidentStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatuses(IncidentStatusManager $incidentStatusManager)
  {
    try
    {
      //get incident statuses
      $incidentStatuses = $incidentStatusManager->getIncidentStatuses();

      //respond with object
      return $this->respond($incidentStatuses);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses/{hashId}", name="getIncidentStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatus($hashId, IncidentStatusManager $incidentStatusManager)
  {
    try
    {
      //get incident status
      $incidentStatus = $incidentStatusManager->getIncidentStatus($hashId);

      //check for valid incident status
      if (!$incidentStatus)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($incidentStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses", name="createIncidentStatus", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createIncidentStatus(Request $req, IncidentStatusManager $incidentStatusManager)
  {
    try
    {
      //create status object
      $status = new IncidentStatus();

      //create form object for status
      $form = $this->createForm(
        IncidentStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $incidentStatusManager->createIncidentStatus($status);

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
   * @Route("/api/v1/incidentstatuses/{hashId}", name="updateIncidentStatus", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateIncidentStatus($hashId, Request $req, IncidentStatusManager $incidentStatusManager)
  {
    try
    {
      //get status from database
      $status = $incidentStatusManager->getIncidentStatus($hashId);

      if (!$status)
        throw new \Exception('Invalid object');

      //create form object for status
      $form = $this->createForm(
        IncidentStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $incidentStatusManager->updateIncidentStatus();

        //respond with object
        return $this->respond($status);
      }

      //render incident status edit page
      return $this->render('dashboard/incidentstatus/edit.html.twig', [
        'form' => $form->createView()
      ]);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses/{hashId}", name="deleteIncidentStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteIncidentStatus($hashId, IncidentStatusManager $incidentStatusManager)
  {
    try
    {
      //get incident status
      $incidentStatus = $incidentStatusManager->getIncidentStatus($hashId);

      //check for valid incident status
      if (!$incidentStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete incident status
      $incidentStatusManager->deleteIncidentStatus($incidentStatus);

      //respond with object
      return $this->respond($incidentStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
