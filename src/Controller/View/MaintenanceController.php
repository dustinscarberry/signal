<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Service\Manager\MaintenanceManager;

class MaintenanceController extends AbstractController
{
  /**
   * @Route("/dashboard/maintenance", name="viewAllMaintenance")
   */
  public function viewall()
  {
    $maintenance = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/maintenance/viewall.html.twig', [
      'maintenance' => $maintenance
    ]);
  }

  /**
   * @Route("/dashboard/maintenance/add", name="addMaintenance")
   */
  public function add(Request $request, MaintenanceManager $maintenanceManager)
  {
    //create maintenance object
    $maintenance = new Maintenance();

    //create form object for maintenance
    $form = $this->createForm(MaintenanceType::class, $maintenance);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $maintenanceManager->createMaintenance(
        $maintenance,
        $form->get('updateServiceStatuses')
      );

      $this->addFlash('success', 'Maintenance item created');
      return $this->redirectToRoute('viewAllMaintenance');
    }

    //render maintenance add page
    return $this->render('dashboard/maintenance/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/maintenance/{hashId}", name="editMaintenance")
   */
  public function edit($hashId, Request $request, MaintenanceManager $maintenanceManager)
  {
    //get maintenance from database
    $maintenance = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findByHashId($hashId);

    //get original updates and services to compare against
    $originalServices = MaintenanceManager::getCurrentServices($maintenance);
    $originalUpdates = MaintenanceManager::getCurrentUpdates($maintenance);

    //create form object for maintenance
    $form = $this->createForm(MaintenanceType::class, $maintenance);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $maintenanceManager->createMaintenance(
        $maintenance,
        $form->get('updateServiceStatuses'),
        $originalServices,
        $originalUpdates
      );

      $this->addFlash('success', 'Maintenance item updated');
      return $this->redirectToRoute('viewMaintenance');
    }

    //render maintenance add page
    return $this->render('dashboard/maintenance/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
