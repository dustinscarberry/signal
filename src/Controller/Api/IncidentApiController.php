<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Incident;
use App\Form\IncidentType;
use App\Service\Manager\IncidentManager;

class IncidentApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidents", name="getIncidents", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidents(IncidentManager $incidentManager)
  {
    try
    {
      $incidents = $incidentManager->getIncidents();
      return $this->respond($incidents);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents/{hashId}", name="getIncident", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncident($hashId, IncidentManager $incidentManager)
  {
    try
    {
      //get incident
      $incident = $incidentManager->getIncident($hashId);

      //check for valid incident
      if (!$incident)
        return $this->respondWithErrors(['Invalid data']);

      return $this->respond($incident);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents", name="createIncident", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function createIncident(Request $req, IncidentManager $incidentManager)
  {
    try
    {
      //create incident object
      $incident = new Incident();

      //create form object for incident
      $form = $this->createForm(
        IncidentType::class,
        $incident,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $incidentManager->createIncident($incident);

        return $this->respond($incident);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents/{hashId}", name="updateIncident", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function updateIncident($hashId, Request $req, IncidentManager $incidentManager)
  {
    try
    {
      //get incident from database
      $incident = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findByHashId($hashId);

      if (!$incident)
        throw new \Exception('Item not found');

      //get original updates and services to compare against
      $originalServices = IncidentManager::getCurrentServices($incident);
      $originalUpdates = IncidentManager::getCurrentUpdates($incident);

      //create form object for incident
      $form = $this->createForm(
        IncidentType::class,
        $incident,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $incidentManager->updateIncident(
          $incident,
          $originalServices,
          $originalUpdates
        );

        return $this->respond($incident);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents/{hashId}", name="deleteIncident", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteIncident($hashId, IncidentManager $incidentManager)
  {
    try
    {
      //get incident
      $incident = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findByHashId($hashId);

      //check for valid incident
      if (!$incident)
        return $this->respondWithErrors(['Invalid data']);

      //delete incident
      $incidentManager->deleteIncident($incident);

      //respond with object
      return $this->respond($incident);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
