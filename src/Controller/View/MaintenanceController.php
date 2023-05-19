<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Service\Factory\MaintenanceFactory;

class MaintenanceController extends AbstractController
{
  #[Route('/dashboard/maintenance', name: 'viewAllMaintenance')]
  public function viewall(MaintenanceFactory $maintenanceFactory)
  {
    $maintenance = $maintenanceFactory->getMaintenances();

    return $this->render('dashboard/maintenance/viewall.html.twig', [
      'maintenance' => $maintenance
    ]);
  }

  #[Route('/dashboard/maintenance/add', name: 'addMaintenance')]
  public function add(Request $req, MaintenanceFactory $maintenanceFactory)
  {
    $maintenance = new Maintenance();
    $form = $this->createForm(MaintenanceType::class, $maintenance);
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $maintenanceFactory->createMaintenance(
        $maintenance,
        $form->get('updateServiceStatuses')->getData()
      );

      $this->addFlash('success', 'Maintenance item created');
      return $this->redirectToRoute('viewAllMaintenance');
    }

    return $this->render('dashboard/maintenance/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/maintenance/{hashId}', name: 'editMaintenance')]
  public function edit($hashId, Request $req, MaintenanceFactory $maintenanceFactory)
  {
    //get maintenance from database
    $maintenance = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findByHashId($hashId);

    //get original updates and services to compare against
    $originalServices = MaintenanceFactory::getCurrentServices($maintenance);
    $originalUpdates = MaintenanceFactory::getCurrentUpdates($maintenance);

    $form = $this->createForm(MaintenanceType::class, $maintenance);
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $maintenanceFactory->updateMaintenance(
        $maintenance,
        $form->get('updateServiceStatuses')->getData(),
        $originalServices,
        $originalUpdates
      );

      $this->addFlash('success', 'Maintenance item updated');
      return $this->redirectToRoute('viewAllMaintenance');
    }

    //render maintenance add page
    return $this->render('dashboard/maintenance/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}