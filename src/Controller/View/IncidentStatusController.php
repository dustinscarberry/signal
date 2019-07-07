<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\IncidentStatus;
use App\Form\IncidentStatusType;
use App\Service\Manager\IncidentStatusManager;

class IncidentStatusController extends AbstractController
{
  /**
   * @Route("/dashboard/statuses/incident", name="viewIncidentStatuses")
   */
  public function viewall(IncidentStatusManager $incidentStatusManager)
  {
    $statuses = $incidentStatusManager->getIncidentStatuses();

    return $this->render('dashboard/incidentstatus/viewall.html.twig', [
      'incidentStatuses' => $statuses
    ]);
  }

  /**
   * @Route("/dashboard/statuses/incident/add", name="addIncidentStatus")
   */
  public function add(Request $req, IncidentStatusManager $incidentStatusManager)
  {
    //create status object
    $status = new IncidentStatus();

    //create form object for status
    $form = $this->createForm(IncidentStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $incidentStatusManager->createIncidentStatus($status);

      $this->addFlash('success', 'Incident Status created');
      return $this->redirectToRoute('viewIncidentStatuses');
    }

    //render incident status add page
    return $this->render('dashboard/incidentstatus/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/statuses/incident/{hashId}", name="editIncidentStatus")
   */
  public function edit($hashId, Request $req, IncidentStatusManager $incidentStatusManager)
  {
    //get status from database
    $status = $incidentStatusManager->getIncidentStatus($hashId);

    //create form object for status
    $form = $this->createForm(IncidentStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $incidentStatusManager->updateIncidentStatus();

      $this->addFlash('success', 'Incident Status updated');
      return $this->redirectToRoute('viewIncidentStatuses');
    }

    //render incident status edit page
    return $this->render('dashboard/incidentstatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
