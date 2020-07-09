<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\IncidentStatus;
use App\Form\IncidentStatusType;
use App\Service\Factory\IncidentStatusFactory;

class IncidentStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidentstatuses", name="getIncidentStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatuses(IncidentStatusFactory $incidentStatusFactory)
  {
    try
    {
      //get incident statuses
      $incidentStatuses = $incidentStatusFactory->getIncidentStatuses();

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
  public function getIncidentStatus($hashId, IncidentStatusFactory $incidentStatusFactory)
  {
    try
    {
      //get incident status
      $incidentStatus = $incidentStatusFactory->getIncidentStatus($hashId);

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
  public function createIncidentStatus(Request $req, IncidentStatusFactory $incidentStatusFactory)
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
        $incidentStatusFactory->createIncidentStatus($status);

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
  public function updateIncidentStatus($hashId, Request $req, IncidentStatusFactory $incidentStatusFactory)
  {
    try
    {
      //get status from database
      $status = $incidentStatusFactory->getIncidentStatus($hashId);

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
        $incidentStatusFactory->updateIncidentStatus();

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
  public function deleteIncidentStatus($hashId, IncidentStatusFactory $incidentStatusFactory)
  {
    try
    {
      //get incident status
      $incidentStatus = $incidentStatusFactory->getIncidentStatus($hashId);

      //check for valid incident status
      if (!$incidentStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete incident status
      $incidentStatusFactory->deleteIncidentStatus($incidentStatus);

      //respond with object
      return $this->respond($incidentStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
