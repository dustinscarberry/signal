<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Incident;
use App\Entity\ServiceStatusHistory;
use App\Form\IncidentType;
use App\Service\Mail\Mailer\IncidentCreatedMailer;
use App\Service\Mail\Mailer\IncidentUpdatedMailer;

class IncidentController extends AbstractController
{
  /**
   * @Route("/dashboard/incidents", name="viewIncidents")
   */
  public function viewall()
  {
    $incidents = $this->getDoctrine()
      ->getRepository(Incident::class)
      ->findAllNotDeleted();

    return $this->render('dashboard/incident/viewall.html.twig', [
      'incidents' => $incidents
    ]);
  }

  /**
   * @Route("/dashboard/incidents/add")
   */
  public function add(Request $request, IncidentCreatedMailer $incidentCreatedMailer)
  {
    //create incident object
    $incident = new Incident();

    //create form object for incident
    $form = $this->createForm(IncidentType::class, $incident);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $incident = $form->getData();
      $em = $this->getDoctrine()->getManager();

      //set user
      $incident->setCreatedBy($this->getUser());

      //update service statuses and store histories
      foreach ($incident->getIncidentServices() as $service)
      {
        if ($service->getStatus() != $service->getService()->getStatus())
        {
          $service->getService()->setStatus($service->getStatus());

          $serviceStatusHistory = new ServiceStatusHistory();
          $serviceStatusHistory->setService($service->getService());
          $serviceStatusHistory->setStatus($service->getStatus());
          $em->persist($serviceStatusHistory);
        }
      }

      $em->persist($incident);
      $em->flush();

      //send email if services included
      if ($incident->getIncidentServices())
        $incidentCreatedMailer->send($incident);

      $this->addFlash('success', 'Incident created');
      return $this->redirectToRoute('viewIncidents');
    }

    //render incident add page
    return $this->render('dashboard/incident/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/incidents/{hashId}", name="editIncident")
   */
  public function edit(
    $hashId,
    Request $request,
    IncidentUpdatedMailer $incidentUpdatedMailer
  )
  {
    //get incident from database
    $incident = $this->getDoctrine()
      ->getRepository(Incident::class)
      ->findByHashId($hashId);

    if (!$incident)
      throw new \Exception('Incident not found');

    //get array copy of original services
    $originalServices = new ArrayCollection();

    foreach ($incident->getIncidentServices() as $service)
      $originalServices->add($service);

    //get array copy of original updates
    $originalUpdates = new ArrayCollection();

    foreach ($incident->getIncidentUpdates() as $update)
      $originalUpdates->add($update);

    //create form object for incident
    $form = $this->createForm(IncidentType::class, $incident);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      //get latest incident data
      $incident = $form->getData();

      $em = $this->getDoctrine()->getManager();

      //remove deleted services from database
      foreach ($originalServices as $service)
      {
        if ($incident->getIncidentServices()->contains($service) === false)
          $em->remove($service);
      }

      //update service statuses and store histories
      foreach ($incident->getIncidentServices() as $service)
      {
        if ($service->getStatus() != $service->getService()->getStatus())
        {
          $service->getService()->setStatus($service->getStatus());

          $serviceStatusHistory = new ServiceStatusHistory();
          $serviceStatusHistory->setService($service->getService());
          $serviceStatusHistory->setStatus($service->getStatus());
          $em->persist($serviceStatusHistory);
        }
      }

      //remove deleted updates from database
      foreach ($originalUpdates as $update)
      {
        if ($incident->getIncidentUpdates()->contains($update) === false)
          $em->remove($update);
      }

      //set status and user of new updates
      foreach ($incident->getIncidentUpdates() as $update)
      {
        if ($originalUpdates->contains($update) === false)
        {
           $update->setStatus($incident->getStatus());
           $update->setCreatedBy($this->getUser());
        }
      }

      $em->flush();

      //send email if services included
      if ($incident->getIncidentServices())
        $incidentUpdatedMailer->send($incident);

      $this->addFlash('success', 'Incident updated');
      return $this->redirectToRoute('viewIncidents');
    }

    //render incident add page
    return $this->render('dashboard/incident/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
