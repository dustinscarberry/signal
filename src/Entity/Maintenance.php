<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\Generator\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\MaintenanceRepository")
* @ORM\Table(indexes={@ORM\Index(name="maintenance_hashid_idx", columns={"hash_id"})})
* @ORM\HasLifecycleCallbacks
*/
class Maintenance implements JsonSerializable
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
  * @ORM\Column(type="text")
  */
  private $purpose;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\MaintenanceStatus", inversedBy="maintenances")
  * @ORM\JoinColumn(nullable=false)
  */
  private $status;

  /**
  * @ORM\Column(type="integer")
  */
  private $scheduledFor;

  /**
  * @ORM\Column(type="boolean")
  */
  private $visibility;

  /**
  * @ORM\Column(type="integer")
  */
  private $created;

  /**
  * @ORM\Column(type="integer")
  */
  private $updated;

  /**
  * @ORM\OneToMany(targetEntity="App\Entity\MaintenanceUpdate", mappedBy="maintenance", cascade={"persist"})
  */
  private $maintenanceUpdates;

  /**
  * @ORM\OneToMany(targetEntity="App\Entity\MaintenanceService", mappedBy="maintenance", cascade={"persist"})
  */
  private $maintenanceServices;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $anticipatedEnd;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $deletedOn;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $deletedBy;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="maintenances")
   * @ORM\JoinColumn(nullable=false)
   */
  private $createdBy;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $exchangeCalendarEventId;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $googleCalendarEventId;

  public function __construct()
  {
    $this->maintenanceUpdates = new ArrayCollection();
    $this->maintenanceServices = new ArrayCollection();
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

  public function getPurpose(): ?string
  {
    return $this->purpose;
  }

  public function setPurpose(string $purpose): self
  {
    $this->purpose = $purpose;
    return $this;
  }

  public function getStatus(): ?MaintenanceStatus
  {
    return $this->status;
  }

  public function setStatus(?MaintenanceStatus $status): self
  {
    $this->status = $status;
    return $this;
  }

  public function getScheduledFor(): ?int
  {
    return $this->scheduledFor;
  }

  public function setScheduledFor(int $scheduledFor): self
  {
    $this->scheduledFor = $scheduledFor;
    return $this;
  }

  public function getVisibility(): ?bool
  {
    return $this->visibility;
  }

  public function setVisibility(bool $visibility): self
  {
    $this->visibility = $visibility;
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

  public function getAnticipatedEnd(): ?int
  {
    return $this->anticipatedEnd;
  }

  public function setAnticipatedEnd(?int $anticipatedEnd): self
  {
    $this->anticipatedEnd = $anticipatedEnd;
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

  public function getCreatedBy(): ?User
  {
    return $this->createdBy;
  }

  public function setCreatedBy(?User $createdBy): self
  {
    $this->createdBy = $createdBy;
    return $this;
  }

  public function getExchangeCalendarEventId(): ?string
  {
    return $this->exchangeCalendarEventId;
  }

  public function setExchangeCalendarEventId(?string $exchangeCalendarEventId): self
  {
    $this->exchangeCalendarEventId = $exchangeCalendarEventId;
    return $this;
  }

  public function getGoogleCalendarEventId(): ?string
  {
    return $this->googleCalendarEventId;
  }

  public function setGoogleCalendarEventId(?string $googleCalendarEventId): self
  {
    $this->googleCalendarEventId = $googleCalendarEventId;
    return $this;
  }

  /**
  * @return Collection|MaintenanceUpdate[]
  */
  public function getMaintenanceUpdates(): Collection
  {
    return $this->maintenanceUpdates;
  }

  public function addMaintenanceUpdate(MaintenanceUpdate $maintenanceUpdate): self
  {
    if (!$this->maintenanceUpdates->contains($maintenanceUpdate)) {
      $this->maintenanceUpdates[] = $maintenanceUpdate;
      $maintenanceUpdate->setMaintenance($this);
    }

    return $this;
  }

  public function removeMaintenanceUpdate(MaintenanceUpdate $maintenanceUpdate): self
  {
    if ($this->maintenanceUpdates->contains($maintenanceUpdate)) {
      $this->maintenanceUpdates->removeElement($maintenanceUpdate);
      // set the owning side to null (unless already changed)
      if ($maintenanceUpdate->getMaintenance() === $this) {
        $maintenanceUpdate->setMaintenance(null);
      }
    }

    return $this;
  }

  /**
  * @return Collection|MaintenanceService[]
  */
  public function getMaintenanceServices(): Collection
  {
    return $this->maintenanceServices;
  }

  public function addMaintenanceService(MaintenanceService $maintenanceService): self
  {
    if (!$this->maintenanceServices->contains($maintenanceService)) {
      $this->maintenanceServices[] = $maintenanceService;
      $maintenanceService->setMaintenance($this);
    }

    return $this;
  }

  public function removeMaintenanceService(MaintenanceService $maintenanceService): self
  {
    if ($this->maintenanceServices->contains($maintenanceService)) {
      $this->maintenanceServices->removeElement($maintenanceService);
      // set the owning side to null (unless already changed)
      if ($maintenanceService->getMaintenance() === $this) {
        $maintenanceService->setMaintenance(null);
      }
    }

    return $this;
  }

  public function getServicesString()
  {
    $services = [];
    foreach ($this->maintenanceServices as $maintenanceService)
      $services[] = $maintenanceService->getService()->getName();

    return implode(', ', $services);
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'name' => $this->name,
      'visibility' => $this->visibility,
      'scheduledFor' => $this->scheduledFor,
      'anticipatedEnd' => $this->anticipatedEnd,
      'purpose' => $this->purpose,
      'created' => $this->created,
      'updated' => $this->updated,
      'statusName' => $this->status->getName(),
      'statusType' => $this->status->getType(),
      'createdBy' => $this->createdBy->getFullName(),
      'services' => $this->maintenanceServices->toArray(),
      'updates' => $this->maintenanceUpdates->toArray()
    ];
  }
}
