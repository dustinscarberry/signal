<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ServiceStatus;
use App\Form\ServiceStatusType;
use App\Service\Manager\ServiceStatusManager;

class ServiceStatusController extends AbstractController
{
  /**
   * @Route("/dashboard/statuses/service", name="viewServiceStatuses")
   */
  public function viewall(ServiceStatusManager $serviceStatusManager)
  {
    $statuses = $serviceStatusManager->getServiceStatuses();

    return $this->render('dashboard/servicestatus/viewall.html.twig', [
      'serviceStatuses' => $statuses
    ]);
  }

  /**
   * @Route("/dashboard/statuses/service/add", name="addServiceStatus")
   */
  public function add(Request $req, ServiceStatusManager $serviceStatusManager)
  {
    //create status object
    $status = new ServiceStatus();

    //create form object for status
    $form = $this->createForm(ServiceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceStatusManager->createServiceStatus($status);

      $this->addFlash('success', 'Service Status created');
      return $this->redirectToRoute('viewServiceStatuses');
    }

    //render service status add page
    return $this->render('dashboard/servicestatus/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/statuses/service/{hashId}", name="editServiceStatus")
   */
  public function edit($hashId, Request $req, ServiceStatusManager $serviceStatusManager)
  {
    //get status from database
    $status = $serviceStatusManager->getServiceStatus($hashId);

    //create form object for status
    $form = $this->createForm(ServiceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceStatusManager->updateServiceStatus();

      $this->addFlash('success', 'Service Status updated');
      return $this->redirectToRoute('viewServiceStatuses');
    }

    //render service status edit page
    return $this->render('dashboard/servicestatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
