<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\MaintenanceStatus;
use App\Form\MaintenanceStatusType;

class MaintenanceStatusController extends AbstractController
{
  /**
   * @Route("/dashboard/statuses/maintenance", name="viewMaintenanceStatuses")
   */
  public function viewall()
  {
    $statuses = $this->getDoctrine()
      ->getRepository(MaintenanceStatus::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/maintenancestatus/viewall.html.twig', [
      'maintenanceStatuses' => $statuses
    ]);
  }

  /**
   * @Route("/dashboard/statuses/maintenance/add", name="addMaintenanceStatus")
   */
  public function add(Request $request)
  {
    //create status object
    $status = new MaintenanceStatus();

    //create form object for status
    $form = $this->createForm(MaintenanceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $status = $form->getData();

      $em = $this->getDoctrine()->getManager();
      $em->persist($status);
      $em->flush();

      $this->addFlash('success', 'Maintenance Status created');
      return $this->redirectToRoute('viewMaintenanceStatuses');
    }

    //render maintenance status add page
    return $this->render('dashboard/maintenancestatus/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/statuses/maintenance/{hashId}", name="editMaintenanceStatus")
   */
  public function edit($hashId, Request $request)
  {
    //get status from database
    $status = $this->getDoctrine()
      ->getRepository(MaintenanceStatus::class)
      ->findByHashId($hashId);

    //create form object for status
    $form = $this->createForm(MaintenanceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $status = $form->getData();
      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success', 'Maintenance Status updated');
      return $this->redirectToRoute('viewMaintenanceStatuses');
    }

    //render maintenance status edit page
    return $this->render('dashboard/maintenancestatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
