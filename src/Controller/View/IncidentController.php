<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Incident;
use App\Form\IncidentType;
use App\Service\Factory\IncidentFactory;

class IncidentController extends AbstractController
{
  /**
   * @Route("/dashboard/incidents", name="viewIncidents")
   */
  public function viewall(IncidentFactory $incidentFactory)
  {
    $incidents = $incidentFactory->getIncidents();

    return $this->render('dashboard/incident/viewall.html.twig', [
      'incidents' => $incidents
    ]);
  }

  /**
   * @Route("/dashboard/incidents/add", name="addIncident")
   */
  public function add(Request $req, IncidentFactory $incidentFactory)
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
      $incidentFactory->createIncident($incident);

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
  public function edit($hashId, Request $req, IncidentFactory $incidentFactory)
  {
    //get incident from database
    $incident = $incidentFactory->getIncident($hashId);

    if (!$incident)
      throw new \Exception('Incident not found');

    //get original updates and services to compare against
    $originalServices = IncidentFactory::getCurrentServices($incident);
    $originalUpdates = IncidentFactory::getCurrentUpdates($incident);

    //create form object for incident
    $form = $this->createForm(IncidentType::class, $incident);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $incidentFactory->updateIncident(
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
