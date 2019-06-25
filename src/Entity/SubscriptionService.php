<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\SubscriptionServiceRepository")
*/
class SubscriptionService implements JsonSerializable
{
  /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
  private $id;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\Subscription", inversedBy="blacklistedSubscriptionServices", fetch="EAGER")
  * @ORM\JoinColumn(nullable=false)
  */
  private $subscription;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="subscriptionServices", fetch="EAGER")
  * @ORM\JoinColumn(nullable=false)
  */
  private $service;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getSubscription(): ?Subscription
  {
    return $this->subscription;
  }

  public function setSubscription(?Subscription $subscription): self
  {
    $this->subscription = $subscription;
    return $this;
  }

  public function getService(): ?Service
  {
    return $this->service;
  }

  public function setService(?Service $service): self
  {
    $this->service = $service;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'name' => $this->service->getName()
    ];
  }
}
