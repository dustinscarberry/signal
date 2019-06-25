<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\Subscription;
use App\Entity\SubscriptionService;

class SubscriptionManagement
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function update($subscriptionID)
  {
    $subscription = $this->em->getRepository(Subscription::class)->findOneBy(['hashId' => $subscriptionID]);

    //// TODO: THROW EXCEPTION IF Subscription NOT FOUND

    $blacklistedServices = $subscription->getBlacklistedSubscriptionServices();

    foreach ($this as $key => $services)
    {
      if ($key != 'em' && is_array($services))
      {
        foreach ($services as $key => $serviceEnabled)
        {
          $serviceID = intval(str_replace('service-', '', $key));

          if ($serviceEnabled)//delete from blacklist if contained
          {
            foreach ($blacklistedServices as $blacklistedService)
            {
              if ($blacklistedService->getService()->getId() == $serviceID)
                $subscription->removeBlacklistedSubscriptionServices($blacklistedService);
            }
          }
          else// add to blacklist if not contained
          {
            $found = false;
            foreach($blacklistedServices as $blacklistedService)
            {
              if ($blacklistedService->getService()->getId() == $serviceID)
              {
                $found = true;
                break;
              }
            }

            if (!$found)//not found so add to blacklisted services
            {
              $service = $this->em->getRepository(Service::class)->find($serviceID);

              //// TODO: check service for existance

              $subscriptionService = new SubscriptionService();
              $subscriptionService->setService($service);
              $subscription->addBlacklistedSubscriptionServices($subscriptionService);
            }
          }
        }
      }
    }

    $this->em->flush();
  }

  public function initialize($subscriptionID)
  {
    $serviceCategories = $this->em
      ->getRepository(ServiceCategory::class)
      ->findAll();
    $subscription = $this->em
      ->getRepository(Subscription::class)
      ->findOneBy(['hashId' => $subscriptionID]);
    $blacklistedServices = $subscription->getBlacklistedSubscriptionServices();

    foreach ($serviceCategories as $serviceCategory)
    {
      $indexer = 'serviceCategory-' . $serviceCategory->getId();

      $this->$indexer = [];

      foreach ($serviceCategory->getServices() as $service)
      {
        $blacklistedServiceFound = false;

        foreach ($blacklistedServices as $blacklistedService)
        {
          if ($blacklistedService->getService() == $service)
          {
            $blacklistedServiceFound = true;
            break;
          }
        }

        $this->$indexer['service-' . $service->getId()] = !$blacklistedServiceFound;
      }
    }
  }
}
