<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\Generator\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
* @ORM\Table(indexes={@ORM\Index(name="subscription_hashid_idx", columns={"hash_id"})})
* @ORM\HasLifecycleCallbacks
*/
class Subscription implements JsonSerializable
{
  /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
  private $id;

  /**
   * @ORM\Column(type="string", length=25, unique=true)
   */
  private $hashId;

  /**
  * @ORM\Column(type="string", length=255)
  */
  private $email;

  /**
  * @ORM\OneToMany(targetEntity="App\Entity\SubscriptionService", mappedBy="subscription", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
  */
  private $blacklistedSubscriptionServices;

  /**
  * @ORM\Column(type="integer")
  */
  private $created;

  /**
  * @ORM\Column(type="integer")
  */
  private $updated;

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function updateTimestamps()
  {
    $currentTime = time();
    $this->setUpdated($currentTime);

    if ($this->getCreated() == null)
      $this->setCreated($currentTime);
  }

  /**
   * @ORM\PrePersist
   */
  public function createHashId()
  {
    $this->hashId = HashIdGenerator::generate();
  }

  public function __construct()
  {
    $this->subscriptionServices = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getHashId(): ?string
  {
    return $this->hashId;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;
    return $this;
  }

  public function getCreated(): ?int
  {
    return $this->created;
  }

  public function setCreated(int $created): self
  {
    $this->created = $created;
    return $this;
  }

  public function getUpdated(): ?int
  {
    return $this->updated;
  }

  public function setUpdated(int $updated): self
  {
    $this->updated = $updated;
    return $this;
  }

  /**
  * @return Collection|SubscriptionService[]
  */
  public function getBlacklistedSubscriptionServices(): Collection
  {
    return $this->blacklistedSubscriptionServices;
  }

  public function addBlacklistedSubscriptionServices(SubscriptionService $subscriptionService): self
  {
    if (!$this->blacklistedSubscriptionServices->contains($subscriptionService)) {
      $this->blacklistedSubscriptionServices[] = $subscriptionService;
      $subscriptionService->setSubscription($this);
    }

    return $this;
  }

  public function removeBlacklistedSubscriptionServices(SubscriptionService $subscriptionService): self
  {
    if ($this->blacklistedSubscriptionServices->contains($subscriptionService)) {
      $this->blacklistedSubscriptionServices->removeElement($subscriptionService);
      // set the owning side to null (unless already changed)
      if ($subscriptionService->getSubscription() === $this) {
        $subscriptionService->setSubscription(null);
      }
    }

    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'email' => $this->email,
      'created' => $this->created,
      'updated' => $this->updated,
      'blacklistedServices' => $this->blacklistedSubscriptionServices->toArray()
    ];
  }
}
