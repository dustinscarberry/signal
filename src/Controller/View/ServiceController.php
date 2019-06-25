<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Mail\Mailer\ServiceUpdatedMailer;
use App\Entity\Service;
use App\Entity\ServiceStatusHistory;
use App\Form\ServiceType;

class ServiceController extends AbstractController
{
  /**
   * @Route("/dashboard/services", name="viewServices")
   */
  public function viewall()
  {
    $services = $this->getDoctrine()
      ->getRepository(Service::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/service/viewall.html.twig', [
      'services' => $services
    ]);
  }

  /**
   * @Route("/dashboard/services/add")
   */
  public function add(Request $request)
  {
    //create service object
    $service = new Service();

    //create form object for service
    $form = $this->createForm(ServiceType::class, $service);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $service = $form->getData();
      $em = $this->getDoctrine()->getManager();

      $serviceStatusHistory = new ServiceStatusHistory();
      $serviceStatusHistory->setService($service);
      $serviceStatusHistory->setStatus($service->getStatus());

      $em->persist($service);
      $em->persist($serviceStatusHistory);
      $em->flush();

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
  public function edit(
    $hashId,
    Request $request,
    ServiceUpdatedMailer $serviceUpdatedMailer
  )
  {
    //get service from database
    $service = $this->getDoctrine()
      ->getRepository(Service::class)
      ->findByHashId($hashId);

    //get previous status
    $serviceStatus = $service->getStatus();

    //create form object for service
    $form = $this->createForm(ServiceType::class, $service);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $service = $form->getData();
      $em = $this->getDoctrine()->getManager();

      //add new service status history if changed
      if ($serviceStatus != $service->getStatus())
      {
        $serviceStatusHistory = new ServiceStatusHistory();
        $serviceStatusHistory->setService($service);
        $serviceStatusHistory->setStatus($service->getStatus());
        $em->persist($serviceStatusHistory);

        //send update email
        $serviceUpdatedMailer->send($service);
      }

      $em->flush();

      $this->addFlash('success', 'Service updated');
      return $this->redirectToRoute('viewServices');
    }

    //render service add page
    return $this->render('dashboard/service/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
