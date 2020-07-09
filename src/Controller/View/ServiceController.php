<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Service\Factory\ServiceFactory;

class ServiceController extends AbstractController
{
  /**
   * @Route("/dashboard/services", name="viewServices")
   */
  public function viewall(ServiceFactory $serviceFactory)
  {
    $services = $serviceFactory->getServices();

    return $this->render('dashboard/service/viewall.html.twig', [
      'services' => $services
    ]);
  }

  /**
   * @Route("/dashboard/services/add", name="addService")
   */
  public function add(Request $req, ServiceFactory $serviceFactory)
  {
    //create service object
    $service = new Service();

    //create form object for service
    $form = $this->createForm(ServiceType::class, $service);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceFactory->createService($service);

      $this->addFlash('success', 'Service created');
      return $this->redirectToRoute('viewServices');
    }

    //render service add page
    return $this->render('dashboard/service/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/services/{hashId}", name="editService")
   */
  public function edit($hashId, Request $req, ServiceFactory $serviceFactory)
  {
    //get service from database
    $service = $serviceFactory->getService($hashId);

    //get previous status
    $currentServiceStatus = $serviceFactory->getCurrentServiceStatus($service);

    //create form object for service
    $form = $this->createForm(ServiceType::class, $service);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $serviceFactory->updateService($service, $currentServiceStatus);

      $this->addFlash('success', 'Service updated');
      return $this->redirectToRoute('viewServices');
    }

    //render service add page
    return $this->render('dashboard/service/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
