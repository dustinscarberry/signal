<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\IncidentStatus;
use App\Form\IncidentStatusType;

class IncidentStatusController extends AbstractController
{
  /**
   * @Route("/dashboard/statuses/incident", name="viewIncidentStatuses")
   */
  public function viewall()
  {
    $statuses = $this->getDoctrine()
      ->getRepository(IncidentStatus::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/incidentstatus/viewall.html.twig', [
      'incidentStatuses' => $statuses
    ]);
  }

  /**
   * @Route("/dashboard/statuses/incident/add", name="addIncidentStatus")
   */
  public function add(Request $request)
  {
    //create status object
    $status = new IncidentStatus();

    //create form object for status
    $form = $this->createForm(IncidentStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $status = $form->getData();

      $em = $this->getDoctrine()->getManager();
      $em->persist($status);
      $em->flush();

      $this->addFlash('success', 'Incident Status created');
      return $this->redirectToRoute('viewIncidentStatuses');
    }

    //render incident status add page
    return $this->render('dashboard/incidentstatus/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/statuses/incident/{statusGuid}", name="editIncidentStatus")
   */
  public function edit($statusGuid, Request $request)
  {
    //get status from database
    $status = $this->getDoctrine()
      ->getRepository(IncidentStatus::class)
      ->findByGuid($statusGuid);

    //create form object for status
    $form = $this->createForm(IncidentStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $status = $form->getData();
      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success', 'Incident Status updated');
      return $this->redirectToRoute('viewIncidentStatuses');
    }

    //render incident status edit page
    return $this->render('dashboard/incidentstatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
