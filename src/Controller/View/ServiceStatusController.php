<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ServiceStatus;
use App\Form\ServiceStatusType;

class ServiceStatusController extends AbstractController
{
  /**
   * @Route("/dashboard/statuses/service", name="viewServiceStatuses")
   */
  public function viewall()
  {
    $statuses = $this->getDoctrine()
      ->getRepository(ServiceStatus::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/servicestatus/viewall.html.twig', [
      'serviceStatuses' => $statuses
    ]);
  }

  /**
   * @Route("/dashboard/statuses/service/add", name="addServiceStatus")
   */
  public function add(Request $request)
  {
    //create status object
    $status = new ServiceStatus();

    //create form object for status
    $form = $this->createForm(ServiceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $status = $form->getData();
      $em = $this->getDoctrine()->getManager();

      $em->persist($status);
      $em->flush();

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
  public function edit($hashId, Request $request)
  {
    //get status from database
    $status = $this->getDoctrine()
      ->getRepository(ServiceStatus::class)
      ->findByHashId($hashId);

    //create form object for status
    $form = $this->createForm(ServiceStatusType::class, $status);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $status = $form->getData();
      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success', 'Service Status updated');
      return $this->redirectToRoute('viewServiceStatuses');
    }

    //render service status edit page
    return $this->render('dashboard/servicestatus/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
