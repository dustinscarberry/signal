<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\CustomMetricDatapoint;
use App\Form\CustomMetricDatapointType;
use App\Service\Manager\CustomMetricDatapointManager;

class CustomMetricDatapointApiController extends ApiController
{
  /**
   * @Route("/api/v1/custommetricdatapoints", name="createCustomMetricDatapoint", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createCustomMetricDatapoint(Request $req, CustomMetricDatapointManager $customMetricDatapointManager)
  {
    try
    {
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
      if ($form->isSubmitted() && $form->isValid())
      {
        $customMetricDatapointManager->createCustomMetricDatapoint($datapoint);

        return $this->respond($datapoint);
      }

      return $this->respondWithErrors(['Invalid Data']);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/custommetricdatapoints/{hashId}", name="deleteCustomMetricDatapoint", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteCustomMetricDatapoint($hashId, Request $req, CustomMetricDatapointManager $customMetricDatapointManager)
  {
    try
    {
      //get datapoint
      $datapoint = $customMetricDatapointManager->getCustomMetricDatapoint($hashId);

      //check for valid datapoint
      if (!$datapoint)
        return $this->respondWithErrors(['Invalid data']);

      //delete datapoint
      $customMetricDatapointManager->deleteCustomMetricDatapoint($datapoint);

      //respond with object
      return $this->respond($datapoint);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
