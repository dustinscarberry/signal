<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Incident;
use App\Entity\ServiceStatusHistory;
use App\Form\IncidentType;
use App\Service\Mail\Mailer\IncidentCreatedMailer;
use App\Service\Mail\Mailer\IncidentUpdatedMailer;

class IncidentApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidents", name="getIncidents", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidents()
  {
    try
    {
      $incidents = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findAllNotDeleted();

      return $this->respond($incidents);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents/{guid}", name="getIncident", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncident($guid)
  {
    try
    {
      //get incident
      $incident = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findByGuid($guid);

      //check for valid incident
      if (!$incident)
        return $this->respondWithErrors(['Invalid data']);

      return $this->respond($incident);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents", name="createIncident", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function createIncident(
    Request $req,
    IncidentCreatedMailer $incidentCreatedMailer
  )
  {
    try
    {
      //create incident object
      $incident = new Incident();

      //create form object for incident
      $form = $this->createForm(
        IncidentType::class,
        $incident,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

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

        //set status and user of any updates
        foreach ($incident->getIncidentUpdates() as $update)
        {
           $update->setStatus($incident->getStatus());
           $update->setCreatedBy($this->getUser());
        }

        //persist incident
        $em->persist($incident);
        $em->flush();

        //send email if services included
        if ($incident->getIncidentServices())
          $incidentCreatedMailer->send($incident);

        return $this->respond($incident);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents/{guid}", name="updateIncident", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function updateIncident(
    $guid,
    Request $req,
    IncidentUpdatedMailer $incidentUpdatedMailer
  )
  {
    try
    {
      //get incident from database
      $incident = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findByGuid($guid);

      if (!$incident)
        throw new \Exception('Item not found');

      //get array copy of original services
      $originalServices = new ArrayCollection();

      foreach ($incident->getIncidentServices() as $service)
        $originalServices->add($service);

      //get array copy of original updates
      $originalUpdates = new ArrayCollection();

      foreach ($incident->getIncidentUpdates() as $update)
        $originalUpdates->add($update);

      //create form object for incident
      $form = $this->createForm(
        IncidentType::class,
        $incident,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

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

        return $this->respond($incident);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidents/{guid}", name="deleteIncident", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteIncident($guid)
  {
    try
    {
      //get incident
      $incident = $this->getDoctrine()
        ->getRepository(Incident::class)
        ->findByGuid($guid);

      //check for valid incident
      if (!$incident)
        return $this->respondWithErrors(['Invalid data']);

      //soft delete incident
      $incident->setDeletedOn(time());
      $incident->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($incident);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
