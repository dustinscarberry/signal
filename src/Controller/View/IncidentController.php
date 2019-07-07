<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Incident;
use App\Form\IncidentType;
use App\Service\Manager\IncidentManager;

class IncidentController extends AbstractController
{
  /**
   * @Route("/dashboard/incidents", name="viewIncidents")
   */
  public function viewall(IncidentManager $incidentManager)
  {
    $incidents = $incidentManager->getIncidents();

    return $this->render('dashboard/incident/viewall.html.twig', [
      'incidents' => $incidents
    ]);
  }

  /**
   * @Route("/dashboard/incidents/add", name="addIncident")
   */
  public function add(Request $req, IncidentManager $incidentManager)
  {
    //create incident object
    $incident = new Incident();

    //create form object for incident
    $form = $this->createForm(IncidentType::class, $incident);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $incidentManager->createIncident($incident);

      $this->addFlash('success', 'Incident created');
      return $this->redirectToRoute('viewIncidents');
    }

    //render incident add page
    return $this->render('dashboard/incident/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/incidents/{hashId}", name="editIncident")
   */
  public function edit($hashId, Request $req, IncidentManager $incidentManager)
  {
    //get incident from database
    $incident = $this->getDoctrine()
      ->getRepository(Incident::class)
      ->findByHashId($hashId);

    if (!$incident)
      throw new \Exception('Incident not found');

    //get original updates and services to compare against
    $originalServices = IncidentManager::getCurrentServices($incident);
    $originalUpdates = IncidentManager::getCurrentUpdates($incident);

    //create form object for incident
    $form = $this->createForm(IncidentType::class, $incident);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $incidentManager->updateIncident(
        $incident,
        $originalServices,
        $originalUpdates
      );

      $this->addFlash('success', 'Incident updated');
      return $this->redirectToRoute('viewIncidents');
    }

    //render incident add page
    return $this->render('dashboard/incident/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
