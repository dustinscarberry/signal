<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\IncidentStatus;
use App\Form\IncidentStatusType;

class IncidentStatusApiController extends ApiController
{
  /**
   * @Route("/api/v1/incidentstatuses", name="getIncidentStatuses", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatuses()
  {
    try
    {
      //get incident statuses
      $incidentStatuses = $this->getDoctrine()
        ->getRepository(IncidentStatus::class)
        ->findAllNotDeleted();

      //respond with object
      return $this->respond($incidentStatuses);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses/{hashId}", name="getIncidentStatus", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function getIncidentStatus($hashId)
  {
    try
    {
      //get incident status
      $incidentStatus = $this->getDoctrine()
        ->getRepository(IncidentStatus::class)
        ->findByHashId($hashId);

      //check for valid incident status
      if (!$incidentStatus)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($incidentStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses", name="createIncidentStatus", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createIncidentStatus(Request $req)
  {
    try
    {
      //create status object
      $status = new IncidentStatus();

      //create form object for status
      $form = $this->createForm(
        IncidentStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $status = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($status);
        $em->flush();

        //respond with object
        return $this->respond($status);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses/{hashId}", name="updateIncidentStatus", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateIncidentStatus($hashId, Request $req)
  {
    try
    {
      //get status from database
      $status = $this->getDoctrine()
        ->getRepository(IncidentStatus::class)
        ->findByHashId($hashId);

      if (!$status)
        throw new \Exception('Invalid object');

      //create form object for status
      $form = $this->createForm(
        IncidentStatusType::class,
        $status,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $status = $form->getData();
        $this->getDoctrine()->getManager()->flush();

        //respond with object
        return $this->respond($status);
      }

      //render incident status edit page
      return $this->render('dashboard/incidentstatus/edit.html.twig', [
        'form' => $form->createView()
      ]);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/incidentstatuses/{hashId}", name="deleteIncidentStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
  */
  public function deleteIncidentStatus($hashId)
  {
    try
    {
      //get incident status
      $incidentStatus = $this->getDoctrine()
        ->getRepository(IncidentStatus::class)
        ->findByHashId($hashId);

      //check for valid incident status
      if (!$incidentStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete incident status
      $incidentStatus->setDeletedOn(time());
      $incidentStatus->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($incidentStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
