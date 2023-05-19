<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\IncidentStatus;
use App\Form\IncidentStatusType;
use App\Service\Factory\IncidentStatusFactory;

class IncidentStatusController extends AbstractController
{
  #[Route('/dashboard/statuses/incident', name: 'viewIncidentStatuses')]
  public function viewall(IncidentStatusFactory $incidentStatusFactory)
  {
    $statuses = $incidentStatusFactory->getIncidentStatuses();

    return $this->render('dashboard/incidentstatus/viewall.html.twig', [
      'incidentStatuses' => $statuses
    ]);
  }

  #[Route('/dashboard/statuses/incident/add', name: 'addIncidentStatus')]
  public function add(Request $req, IncidentStatusFactory $incidentStatusFactory)
  {
    $status = new IncidentStatus();
    $form = $this->createForm(IncidentStatusType::class, $status);
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $incidentStatusFactory->createIncidentStatus($status);

      $this->addFlash('success', 'Incident Status created');
      return $this->redirectToRoute('viewIncidentStatuses');
    }

    return $this->render('dashboard/incidentstatus/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/statuses/incident/{hashId}', name: 'editIncidentStatus')]
  public function edit($hashId, Request $req, IncidentStatusFactory $incidentStatusFactory)
  {
    $status = $incidentStatusFactory->getIncidentStatus($hashId);
    $form = $this->createForm(IncidentStatusType::class, $status);
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $incidentStatusFactory->updateIncidentStatus();

      $this->addFlash('success', 'Incident Status updated');
      return $this->redirectToRoute('viewIncidentStatuses');
    }

    return $this->render('dashboard/incidentstatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}