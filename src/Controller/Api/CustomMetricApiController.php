<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\CustomMetric;
use App\Form\CustomMetricType;
use App\Service\Manager\CustomMetricManager;

class CustomMetricApiController extends ApiController
{
  /**
   * @Route("/api/v1/custommetrics", name="getCustomMetrics", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getCustomMetrics(CustomMetricManager $customMetricManager)
  {
    try
    {
      $metrics = $customMetricManager->getCustomMetrics();
      return $this->respond($metrics);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/custommetrics/{hashId}", name="getCustomMetric", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getCustomMetric($hashId, CustomMetricManager $customMetricManager)
  {
    try
    {
      //get item
      $metric = $customMetricManager->getCustomMetric($hashId);

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
  public function createCustomMetric(Request $req, CustomMetricManager $customMetricManager)
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
        $customMetricManager->createCustomMetric($metric);

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
   * @Route("/api/v1/custommetrics/{hashId}", name="updateCustomMetric", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateCustomMetric($hashId, Request $req, CustomMetricManager $customMetricManager)
  {
    try
    {
      //get metric from database
      $metric = $customMetricManager->getCustomMetric($hashId);

      if (!$metric)
        throw new \Exception('Object not found');

      //create form object
      $form = $this->createForm(
        CustomMetricType::class,
        $metric,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data, false);

      //save form data to database if posted and validated
      if ($form->isSubmitted() && $form->isValid())
      {
        $customMetricManager->updateCustomMetric();

        return $this->respond($metric);
      }

      return $this->respondWithErrors(['Invalid data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/custommetrics/{hashId}", name="deleteCustomMetric", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteCustomMetric($hashId, CustomMetricManager $customMetricManager)
  {
    try
    {
      //get metric
      $metric = $customMetricManager->getCustomMetric($hashId);

      //check for valid metric
      if (!$metric)
        return $this->respondWithErrors(['Invalid data']);

      //delete metric
      $customMetricManager->deleteCustomMetric($metric);
      
      //respond with object
      return $this->respond($metric);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
