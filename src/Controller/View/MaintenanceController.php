<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Entity\ServiceStatusHistory;
use App\Entity\ExchangeCalendarEvent;
use App\Entity\GoogleCalendarEvent;
use App\Service\Mail\Mailer\MaintenanceCreatedMailer;
use App\Service\Mail\Mailer\MaintenanceUpdatedMailer;
use App\Service\ExchangeEventGenerator;
use App\Model\AppConfig;

class MaintenanceController extends AbstractController
{
  /**
   * @Route("/dashboard/maintenance", name="viewMaintenance")
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
   * @Route("/dashboard/maintenance/add")
   */
  public function add(
    Request $request,
    MaintenanceCreatedMailer $maintenanceCreatedMailer,
    AppConfig $appConfig
  )
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
      $maintenance = $form->getData();
      $em = $this->getDoctrine()->getManager();

      //set created by
      $maintenance->setCreatedBy($this->getUser());

      //check for status update check
      if ($form->get('updateServiceStatuses'))
      {
        //update maintenance statuses and store histories
        foreach ($maintenance->getMaintenanceServices() as $service)
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
      }

      $em->persist($maintenance);
      $em->flush();

      //send email if services included
      if ($maintenance->getMaintenanceServices())
        $maintenanceCreatedMailer->send($maintenance);

      //add to exchange calendar if enabled
      if ($appConfig->getEnableExchangeCalendarSync())
      {
        $eventId = ExchangeEventGenerator::createEvent($maintenance);

        //save to database
        $exchangeEvent = new ExchangeCalendarEvent();
        $exchangeEvent->setEventId($eventId);
        $exchangeEvent->setMaintenance($maintenance);
        $em->persist($exchangeEvent);
        $em->flush();
      }

      //add to google calender if enabled
      if ($appConfig->getEnableGoogleCalendarSync())
      {
        //$eventId = //
      }

      $this->addFlash('success', 'Maintenance item created');
      return $this->redirectToRoute('viewMaintenance');
    }

    //render maintenance add page
    return $this->render('dashboard/maintenance/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/dashboard/maintenance/{hashId}", name="editMaintenance")
   */
  public function edit(
    $hashId,
    Request $request,
    MaintenanceUpdatedMailer $maintenanceUpdatedMailer,
    AppConfig $appConfig
  )
  {
    //get maintenance from database
    $maintenance = $this->getDoctrine()
      ->getRepository(Maintenance::class)
      ->findByHashId($hashId);

    //get array copy of original services
    $originalServices = new ArrayCollection();

    foreach ($maintenance->getMaintenanceServices() as $service)
      $originalServices->add($service);

    //get array copy of original updates
    $originalUpdates = new ArrayCollection();

    foreach ($maintenance->getMaintenanceUpdates() as $update)
      $originalUpdates->add($update);

    //create form object for maintenance
    $form = $this->createForm(MaintenanceType::class, $maintenance);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      //get latest maintenance data
      $maintenance = $form->getData();

      $em = $this->getDoctrine()->getManager();

      //remove deleted services from database
      foreach ($originalServices as $service)
      {
        if ($maintenance->getMaintenanceServices()->contains($service) === false)
          $em->remove($service);
      }

      //check for status update check
      if ($form->get('updateServiceStatuses'))
      {
        //update maintenance statuses and store histories
        foreach ($maintenance->getMaintenanceServices() as $service)
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
      }

      //remove deleted updates from database
      foreach ($originalUpdates as $update)
      {
        if ($maintenance->getMaintenanceUpdates()->contains($update) === false)
          $em->remove($update);
      }

      //set status and user of new updates
      foreach ($maintenance->getMaintenanceUpdates() as $update)
      {
        if ($originalUpdates->contains($update) === false)
        {
          $update->setStatus($maintenance->getStatus());
          $update->setCreatedBy($this->getUser());
        }
      }

      $em->flush();

      //send email if services included
      if ($maintenance->getMaintenanceServices())
        $maintenanceUpdatedMailer->send($maintenance);

      //update exchange calendar if enabled
      if ($appConfig->getEnableExchangeCalendarSync())
      {
        $exchangeEvent = $maintenance->getExchangeCalendarEvent();

        if ($exchangeEvent)
          ExchangeEventGenerator::updateEvent($maintenance, $exchangeEvent->getEventId());
        else
        {
          $eventId = ExchangeEventGenerator::createEvent($maintenance);

          //save to database
          $exchangeEvent = new ExchangeCalendarEvent();
          $exchangeEvent->setEventId($eventId);
          $exchangeEvent->setMaintenance($maintenance);
          $em->persist($exchangeEvent);
          $em->flush();
        }
      }

      //update google calendar if enabled
      if ($appConfig->getEnableGoogleCalendarSync())
      {

      }

      $this->addFlash('success', 'Maintenance item updated');
      return $this->redirectToRoute('viewMaintenance');
    }

    //render maintenance add page
    return $this->render('dashboard/maintenance/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
