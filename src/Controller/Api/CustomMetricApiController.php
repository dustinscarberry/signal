<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\CustomMetric;
use App\Entity\CustomMetricDatapoint;
use App\Form\CustomMetricType;

class CustomMetricApiController extends ApiController
{
  /**
   * @Route("/api/v1/custommetrics", name="getCustomMetrics", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getCustomMetrics()
  {
    try
    {
      $metrics = $this->getDoctrine()
        ->getRepository(CustomMetric::class)
        ->findAll();

      return $this->respond($metrics);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/custommetrics/{guid}", name="getCustomMetric", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getCustomMetric($guid)
  {
    try
    {
      //get item
      $metric = $this->getDoctrine()
        ->getRepository(CustomMetric::class)
        ->findByGuid($guid);

      //check for valid item
      if (!$metric)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($metric);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/custommetrics", name="createCustomMetric", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createCustomMetric(Request $req)
  {
    try
    {
      $metric = new CustomMetric();
      $form = $this->createForm(
        CustomMetricType::class,
        $metric,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save new widget to database if valid
      if ($form->isSubmitted() && $form->isValid())
      {
        $metric = $form->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($metric);
        $entityManager->flush();

        return $this->respond($metric);
      }

      return $this->respondWithErrors(['Invalid Data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/servicestatuses/{guid}", name="updateServiceStatus", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateServiceStatus($guid, Request $req)
  {
    try
    {
      //get status from database
      $status = $this->getDoctrine()
        ->getRepository(ServiceStatus::class)
        ->findByGuid($guid);

      if (!$status)
        throw new \Exception('Object not found');

      //create form object for status
      $form = $this->createForm(
        ServiceStatusType::class,
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
   * @Route("/api/v1/servicestatuses/{guid}", name="deleteServiceStatus", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteServiceStatus($guid)
  {
    try
    {
      //get service status
      $serviceStatus = $this->getDoctrine()
        ->getRepository(ServiceStatus::class)
        ->findByGuid($guid);

      //check for valid service status
      if (!$serviceStatus)
        return $this->respondWithErrors(['Invalid data']);

      //delete service status
      $serviceStatus->setDeletedOn(time());
      $serviceStatus->setDeletedBy($this->getUser());
      $this->getDoctrine()->getManager()->flush();

      //respond with object
      return $this->respond($serviceStatus);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
