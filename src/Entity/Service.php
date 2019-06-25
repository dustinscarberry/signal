<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 * @ORM\Table(indexes={@ORM\Index(name="service_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Service implements JsonSerializable
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
  private $name;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $description;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\ServiceCategory", inversedBy="services")
   */
  private $serviceCategory;

  /**
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $updated;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\ServiceStatusHistory", mappedBy="service")
   */
  private $serviceStatusHistories;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\ServiceStatus", inversedBy="services")
   * @ORM\JoinColumn(nullable=false)
   */
  private $status;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\SubscriptionService", mappedBy="service")
   */
  private $subscriptionServices;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $deletedOn;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $deletedBy;

  public function __construct()
  {
    $this->serviceStatusHistories = new ArrayCollection();
    $this->subscriptionServices = new ArrayCollection();
  }

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

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getHashId(): ?string
  {
    return $this->hashId;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): self
  {
    $this->description = $description;
    return $this;
  }

  public function getServiceCategory(): ?ServiceCategory
  {
    return $this->serviceCategory;
  }

  public function setServiceCategory(?ServiceCategory $serviceCategory): self
  {
    $this->serviceCategory = $serviceCategory;
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

  public function getStatus(): ?ServiceStatus
  {
    return $this->status;
  }

  public function setStatus(?ServiceStatus $status): self
  {
    $this->status = $status;
    return $this;
  }

  /**
   * @return Collection|ServiceStatusHistory[]
   */
  public function getServiceStatusHistories(): Collection
  {
    return $this->serviceStatusHistories;
  }

  public function addServiceStatusHistory(ServiceStatusHistory $serviceStatusHistory): self
  {
    if (!$this->serviceStatusHistories->contains($serviceStatusHistory)) {
      $this->serviceStatusHistories[] = $serviceStatusHistory;
      $serviceStatusHistory->setService($this);
    }

    return $this;
  }

  public function removeServiceStatusHistory(ServiceStatusHistory $serviceStatusHistory): self
  {
    if ($this->serviceStatusHistories->contains($serviceStatusHistory)) {
      $this->serviceStatusHistories->removeElement($serviceStatusHistory);
      // set the owning side to null (unless already changed)
      if ($serviceStatusHistory->getService() === $this) {
        $serviceStatusHistory->setService(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|SubscriptionService[]
   */
  public function getSubscriptionServices(): Collection
  {
    return $this->subscriptionServices;
  }

  public function addSubscriptionService(SubscriptionService $subscriptionService): self
  {
    if (!$this->subscriptionServices->contains($subscriptionService)) {
      $this->subscriptionServices[] = $subscriptionService;
      $subscriptionService->setService($this);
    }

    return $this;
  }

  public function removeSubscriptionService(SubscriptionService $subscriptionService): self
  {
    if ($this->subscriptionServices->contains($subscriptionService)) {
      $this->subscriptionServices->removeElement($subscriptionService);
      // set the owning side to null (unless already changed)
      if ($subscriptionService->getService() === $this) {
        $subscriptionService->setService(null);
      }
    }

    return $this;
  }

  public function getDeletedOn(): ?int
  {
    return $this->deletedOn;
  }

  public function setDeletedOn(?int $deletedOn): self
  {
    $this->deletedOn = $deletedOn;
    return $this;
  }

  public function getDeletedBy(): ?User
  {
    return $this->deletedBy;
  }

  public function setDeletedBy(?User $deletedBy): self
  {
    $this->deletedBy = $deletedBy;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'name' => $this->name,
      'description' => $this->description,
      'status' => $this->status->getName(),
      'statusType' => $this->status->getType(),
      'created' => $this->created,
      'updated' => $this->updated
    ];
  }
}
