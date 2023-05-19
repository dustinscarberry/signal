<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use Exception;
use App\Entity\CustomMetric;
use App\Form\CustomMetricType;
use App\Service\Factory\CustomMetricFactory;

class CustomMetricApiController extends ApiController
{
  #[Route('/api/v1/custommetrics', name: 'getCustomMetrics', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getCustomMetrics(CustomMetricFactory $customMetricFactory)
  {
    try {
      $metrics = $customMetricFactory->getCustomMetrics();
      return $this->respond($metrics);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/custommetrics/{hashId}', name: 'getCustomMetric', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getCustomMetric($hashId, CustomMetricFactory $customMetricFactory)
  {
    try {
      //get item
      $metric = $customMetricFactory->getCustomMetric($hashId);

      //check for valid item
      if (!$metric)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($metric);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/custommetrics', name: 'createCustomMetric', methods: ['POST'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function createCustomMetric(Request $req, CustomMetricFactory $customMetricFactory)
  {
    try {
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
      if ($form->isSubmitted() && $form->isValid()) {
        $customMetricFactory->createCustomMetric($metric);
        return $this->respond($metric);
      }

      return $this->respondWithErrors(['Invalid Data']);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/custommetrics/{hashId}', name: 'updateCustomMetric', methods: ['PATCH'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function updateCustomMetric($hashId, Request $req, CustomMetricFactory $customMetricFactory)
  {
    try {
      //get metric from database
      $metric = $customMetricFactory->getCustomMetric($hashId);

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
      if ($form->isSubmitted() && $form->isValid()) {
        $customMetricFactory->updateCustomMetric();
        return $this->respond($metric);
      }

      return $this->respondWithErrors(['Invalid data']);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/custommetrics/{hashId}', name: 'deleteCustomMetric', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteCustomMetric($hashId, CustomMetricFactory $customMetricFactory)
  {
    try {
      //get metric
      $metric = $customMetricFactory->getCustomMetric($hashId);

      //check for valid metric
      if (!$metric)
        return $this->respondWithErrors(['Invalid data']);

      //delete metric
      $customMetricFactory->deleteCustomMetric($metric);
      
      //respond with object
      return $this->respond($metric);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
