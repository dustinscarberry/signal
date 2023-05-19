<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use Exception;
use App\Entity\CustomMetricDatapoint;
use App\Form\CustomMetricDatapointType;
use App\Service\Factory\CustomMetricDatapointFactory;

class CustomMetricDatapointApiController extends ApiController
{
  #[Route('/api/v1/custommetricdatapoints', name: 'createCustomMetricDatapoint', methods: ['POST'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function createCustomMetricDatapoint(Request $req, CustomMetricDatapointFactory $customMetricDatapointFactory)
  {
    try {
      $datapoint = new CustomMetricDatapoint();

      $form = $this->createForm(
        CustomMetricDatapointType::class,
        $datapoint,
        ['csrf_protection' => false]
      );

      //submit form
      $data = json_decode($req->getContent(), true);
      $form->submit($data);

      //save new widget to database if valid
      if ($form->isSubmitted() && $form->isValid()) {
        $customMetricDatapointFactory->createCustomMetricDatapoint($datapoint);
        return $this->respond($datapoint);
      }

      return $this->respondWithErrors(['Invalid Data']);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/custommetricdatapoints/{hashId}', name: 'deleteCustomMetricDatapoint', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteCustomMetricDatapoint($hashId, Request $req, CustomMetricDatapointFactory $customMetricDatapointFactory)
  {
    try {
      //get datapoint
      $datapoint = $customMetricDatapointFactory->getCustomMetricDatapoint($hashId);

      //check for valid datapoint
      if (!$datapoint)
        return $this->respondWithErrors(['Invalid data']);

      //delete datapoint
      $customMetricDatapointFactory->deleteCustomMetricDatapoint($datapoint);

      //respond with object
      return $this->respond($datapoint);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
