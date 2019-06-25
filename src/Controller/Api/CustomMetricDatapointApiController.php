<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\CustomMetric;
use App\Entity\CustomMetricDatapoint;
use App\Form\CustomMetricType;
use App\Form\CustomMetricDatapointType;

class CustomMetricDatapointApiController extends ApiController
{
  /**
   * @Route("/api/v1/custommetricdatapoints", name="createCustomMetricDatapoint", methods={"POST"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function createCustomMetricDatapoint(Request $req)
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
        $datapoint = $form->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($datapoint);
        $entityManager->flush();

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
  public function deleteCustomMetricDatapoint($hashId, Request $req)
  {
    try
    {
      //get datapoint
      $datapoint = $this->getDoctrine()
        ->getRepository(CustomMetricDatapoint::class)
        ->findByHashId($hashId);

      //check for valid datapoint
      if (!$datapoint)
        return $this->respondWithErrors(['Invalid data']);

      //delete datapoint
      $em = $this->getDoctrine()->getManager();
      $em->remove($datapoint);
      $em->flush();

      //respond with object
      return $this->respond($datapoint);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
