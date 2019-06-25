<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Subscription;

class SubscriptionApiController extends ApiController
{
  /**
   * @Route("/api/v1/subscriptions", name="getSubscriptions", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getSubscriptions()
  {
    try
    {
      //get subscriptions
      $subscriptions = $this->getDoctrine()
        ->getRepository(Subscription::class)
        ->findAll();

      //respond with object
      return $this->respond($subscriptions);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/subscriptions/{hashId}", name="getSubscription", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getSubscription($hashId)
  {
    try
    {
      //get subscription
      $subscription = $this->getDoctrine()
        ->getRepository(Subscription::class)
        ->findByHashId($hashId);

      //check for valid subscription
      if (!$subscription)
        return $this->respondWithErrors(['Invalid data']);

      //respond with object
      return $this->respond($subscription);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  /**
   * @Route("/api/v1/subscriptions/{hashId}", name="deleteSubscription", methods={"DELETE"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function deleteSubscription($hashId)
  {
    try
    {
      //get subscription
      $subscription = $this->getDoctrine()
        ->getRepository(Subscription::class)
        ->findByHashId($hashId);

      //check for valid subscription
      if (!$subscription)
        return $this->respondWithErrors(['Invalid data']);

      //delete subscription
      $em = $this->getDoctrine()->getManager();
      $em->remove($subscription);
      $em->flush();

      //respond with object
      return $this->respond($subscription);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
