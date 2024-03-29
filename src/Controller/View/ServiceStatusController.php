<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ServiceStatus;
use App\Form\ServiceStatusType;
use App\Service\Factory\ServiceStatusFactory;

class ServiceStatusController extends AbstractController
{
  #[Route('/dashboard/statuses/service', name: 'viewServiceStatuses')]
  public function viewall(ServiceStatusFactory $serviceStatusFactory)
  {
    $statuses = $serviceStatusFactory->getServiceStatuses();

    return $this->render('dashboard/servicestatus/viewall.html.twig', [
      'serviceStatuses' => $statuses
    ]);
  }

  #[Route('/dashboard/statuses/service/add', name: 'addServiceStatus')]
  public function add(Request $req, ServiceStatusFactory $serviceStatusFactory)
  {
    $status = new ServiceStatus();
    $form = $this->createForm(ServiceStatusType::class, $status);
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $serviceStatusFactory->createServiceStatus($status);

      $this->addFlash('success', 'Service Status created');
      return $this->redirectToRoute('viewServiceStatuses');
    }

    return $this->render('dashboard/servicestatus/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/statuses/service/{hashId}', name: 'editServiceStatus')]
  public function edit($hashId, Request $req, ServiceStatusFactory $serviceStatusFactory)
  {
    $status = $serviceStatusFactory->getServiceStatus($hashId);
    $form = $this->createForm(ServiceStatusType::class, $status);
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $serviceStatusFactory->updateServiceStatus();

      $this->addFlash('success', 'Service Status updated');
      return $this->redirectToRoute('viewServiceStatuses');
    }

    return $this->render('dashboard/servicestatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}