<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\MaintenanceStatus;
use App\Form\MaintenanceStatusType;
use App\Service\Manager\MaintenanceStatusManager;

class MaintenanceStatusController extends AbstractController
{
  /**
   * @Route("/dashboard/statuses/maintenance", name="viewMaintenanceStatuses")
   */
  public function viewall(MaintenanceStatusManager $maintenanceStatusManager)
  {
    $statuses = $maintenanceStatusManager->getMaintenanceStatuses();

    return $this->render('dashboard/maintenancestatus/viewall.html.twig', [
      'maintenanceStatuses' => $statuses
    ]);
  }

  /**
   * @Route("/dashboard/statuses/maintenance/add", name="addMaintenanceStatus")
   */
  public function add(Request $req, MaintenanceStatusManager $maintenanceStatusManager)
  {
    //create status object
    $status = new MaintenanceStatus();

    //create form object for status
    $form = $this->createForm(MaintenanceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $maintenanceStatusManager->createMaintenanceStatus($status);

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
  public function edit($hashId, Request $req, MaintenanceStatusManager $maintenanceStatusManager)
  {
    //get status from database
    $status = $maintenanceStatusManager->getMaintenanceStatus($hashId);

    //create form object for status
    $form = $this->createForm(MaintenanceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $maintenanceStatusManager->updateMaintenanceStatus();

      $this->addFlash('success', 'Maintenance Status updated');
      return $this->redirectToRoute('viewMaintenanceStatuses');
    }

    //render maintenance status edit page
    return $this->render('dashboard/maintenancestatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
