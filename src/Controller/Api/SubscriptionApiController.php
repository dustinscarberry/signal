<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Manager\SubscriptionManager;

class SubscriptionApiController extends ApiController
{
  /**
   * @Route("/api/v1/subscriptions", name="getSubscriptions", methods={"GET"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function getSubscriptions(SubscriptionManager $subscriptionManager)
  {
    try
    {
      //get subscriptions
      $subscriptions = $subscriptionManager->getSubscriptions();
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
  public function getSubscription($hashId, SubscriptionManager $subscriptionManager)
  {
    try
    {
      //get subscription
      $subscription = $subscriptionManager->getSubscription($hashId);

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
  public function deleteSubscription($hashId, SubscriptionManager $subscriptionManager)
  {
    try
    {
      //get subscription
      $subscription = $subscriptionManager->getSubscription($hashId);

      //check for valid subscription
      if (!$subscription)
        return $this->respondWithErrors(['Invalid data']);

      //delete subscription
      $subscriptionManager->deleteSubscription($subscription);

      //respond with object
      return $this->respond($subscription);
    }
    catch (\Exception $e)
    {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}
