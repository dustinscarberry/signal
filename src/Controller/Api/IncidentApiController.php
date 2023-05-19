<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use Exception;
use App\Entity\Incident;
use App\Form\IncidentType;
use App\Service\Factory\IncidentFactory;

class IncidentApiController extends ApiController
{
  #[Route('/api/v1/incidents', name: 'getIncidents', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getIncidents(IncidentFactory $incidentFactory)
  {
    try {
      $incidents = $incidentFactory->getIncidents();
      return $this->respond($incidents);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/incidents/{hashId}', name: 'getIncident', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getIncident($hashId, IncidentFactory $incidentFactory)
  {
    try {
      //get incident
      $incident = $incidentFactory->getIncident($hashId);

      //check for valid incident
      if (!$incident)
        return $this->respondWithErrors(['Invalid data']);

      return $this->respond($incident);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/incidents', name: 'createIncident', methods: ['POST'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function createIncident(Request $req, IncidentFactory $incidentFactory)
  {
    try {
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
      if ($form->isSubmitted() && $form->isValid()) {
        $incidentFactory->createIncident($incident);
        return $this->respond($incident);
      }

      return $this->respondWithErrors(['Invalid data']);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/incidents/{hashId}', name: 'updateIncident', methods: ['PATCH'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function updateIncident($hashId, Request $req, IncidentFactory $incidentFactory)
  {
    try {
      //get incident from database
      $incident = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findByHashId($hashId);

      if (!$incident)
        throw new \Exception('Item not found');

      //get original updates and services to compare against
      $originalServices = IncidentFactory::getCurrentServices($incident);
      $originalUpdates = IncidentFactory::getCurrentUpdates($incident);

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
      if ($form->isSubmitted() && $form->isValid()) {
        $incidentFactory->updateIncident(
          $incident,
          $originalServices,
          $originalUpdates
        );

        return $this->respond($incident);
      }

      return $this->respondWithErrors(['Invalid data']);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/incidents/{hashId}', name: 'deleteIncident', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteIncident($hashId, IncidentFactory $incidentFactory)
  {
    try {
      //get incident
      $incident = $incidentFactory->getIncident($hashId);

      //check for valid incident
      if (!$incident)
        return $this->respondWithErrors(['Invalid data']);

      //delete incident
      $incidentFactory->deleteIncident($incident);

      //respond with object
      return $this->respond($incident);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}