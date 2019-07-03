<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Maintenance;
use App\Entity\ExchangeCalendarEvent;
use App\Form\MaintenanceType;
use App\Service\Mail\Mailer\MaintenanceCreatedMailer;
use App\Service\Mail\Mailer\MaintenanceUpdatedMailer;
use App\Service\ExchangeEventGenerator;
use App\Model\AppConfig;

class MaintenanceApiController extends ApiController
{
  /**
   * @Route("/api/v1/maintenance", name="getMaintenances", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getMaintenances()
  {
    try
    {
      //get maintenance
      $maintenances = $this->getDoctrine()
        ->getRepository(Maintenance::class)
        ->findAllNotDeleted();

      //respond with object
      return $this->respond($maintenances);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance/{hashId}", name="getMaintenance", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getMaintenance($hashId)
  {
    try
    {
      //get maintenance
      $maintenance = $this->getDoctrine()
        ->getRepository(Maintenance::class)
        ->findByHashId($hashId);

      //check for valid maintenance
      if (!$maintenance)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($maintenance);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance", name="createMaintenance", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function createMaintenance(
    Request $req,
    MaintenanceCreatedMailer $maintenanceCreatedMailer,
    AppConfig $appConfig
  )
  {
    try
    {
      //create maintenance object
      $maintenance = new Maintenance();

      //create form object for maintenance
      $form = $this->createForm(
        MaintenanceType::class,
        $maintenance,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

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

        //set status and user of any updates
        foreach ($maintenance->getMaintenanceUpdates() as $update)
        {
          $update->setStatus($maintenance->getStatus());
          $update->setCreatedBy($this->getUser());
        }

        //persist maintenance
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

        return $this->respond($maintenance);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance/{hashId}", name="updateMaintenance", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function updateMaintenance(
    $hashId,
    Request $req,
    MaintenanceUpdatedMailer $maintenanceUpdatedMailer,
    AppConfig $appConfig
  )
  {
    try
    {
      //get maintenance from database
      $maintenance = $this->getDoctrine()
        ->getRepository(Maintenance::class)
        ->findByHashId($hashId);

      if (!$maintenance)
        throw new \Exception('Item not found');

      //get array copy of original services
      $originalServices = new ArrayCollection();

      foreach ($maintenance->getMaintenanceServices() as $service)
        $originalServices->add($service);

      //get array copy of original updates
      $originalUpdates = new ArrayCollection();

      foreach ($maintenance->getMaintenanceUpdates() as $update)
        $originalUpdates->add($update);

      //create form object for maintenance
      $form = $this->createForm(
        MaintenanceType::class,
        $maintenance,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

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

        return $this->respond($maintenance);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/maintenance/{hashId}", name="deleteMaintenance", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteMaintenance($hashId, AppConfig $appConfig)
  {
    try
    {
      //get maintenance
      $maintenance = $this->getDoctrine()
        ->getRepository(Maintenance::class)
        ->findByHashId($hashId);

      //check for valid maintenance
      if (!$maintenance)
        return $this->respondWithErrors(['Invalid data']);

      //delete from exchange calendar if enabled
      if ($appConfig->getEnableExchangeCalendarSync())
      {
        //delete exchange event if found
        $exchangeEvent = $maintenance->getExchangeCalendarEvent();
        if ($exchangeEvent)
        {
          ExchangeEventGenerator::deleteEvent($exchangeEvent->getEventId());
        }
      }

      //delete maintenance
      $maintenance->setDeletedOn(time());
      $maintenance->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($maintenance);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
