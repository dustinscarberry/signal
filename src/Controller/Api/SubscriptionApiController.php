<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use Exception;
use App\Service\Factory\SubscriptionFactory;

class SubscriptionApiController extends ApiController
{
  #[Route('/api/v1/subscriptions', name: 'getSubscriptions', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getSubscriptions(SubscriptionFactory $subscriptionFactory)
  {
    try {
      $subscriptions = $subscriptionFactory->getSubscriptions();
      return $this->respond($subscriptions);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/subscriptions/{hashId}', name: 'getSubscription', methods: ['GET'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function getSubscription($hashId, SubscriptionFactory $subscriptionFactory)
  {
    try {
      //get subscription
      $subscription = $subscriptionFactory->getSubscription($hashId);

      //check for valid subscription
      if (!$subscription)
        return $this->respondWithErrors(['Invalid data']);
      
      return $this->respond($subscription);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }

  #[Route('/api/v1/subscriptions/{hashId}', name: 'deleteSubscription', methods: ['DELETE'])]
  #[IsGranted(new Expression("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')"))]
  public function deleteSubscription($hashId, SubscriptionFactory $subscriptionFactory)
  {
    try {
      //get subscription
      $subscription = $subscriptionFactory->getSubscription($hashId);

      //check for valid subscription
      if (!$subscription)
        return $this->respondWithErrors(['Invalid data']);

      //delete subscription
      $subscriptionFactory->deleteSubscription($subscription);

      //respond with object
      return $this->respond($subscription);
    } catch (Exception $e) {
      return $this->respondWithErrors([$e->getMessage()]);
    }
  }
}