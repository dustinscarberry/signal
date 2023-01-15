<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\Generator\HashIdGenerator;
use App\Repository\IncidentRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: IncidentRepository::class)]
#[ORM\Index(name: 'incident_hashid_idx', columns: ['hash_id'])]
#[ORM\HasLifecycleCallbacks]
class Incident implements JsonSerializable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 25, unique: true)]
  private $hashId;

  #[ORM\Column(type: 'string', length: 255)]
  private $name;

  #[ORM\Column(type: 'boolean')]
  private $visibility;

  #[ORM\Column(type: 'integer')]
  private $occurred;

  #[ORM\Column(type: 'text', nullable: true)]
  private $message;

  #[ORM\Column(type: 'integer')]
  private $created;

  #[ORM\Column(type: 'integer')]
  private $updated;

  #[ORM\ManyToOne(targetEntity: IncidentStatus::class, inversedBy: 'incidents')]
  #[ORM\JoinColumn(nullable: false)]
  private $status;

  #[ORM\OneToMany(targetEntity: IncidentService::class, mappedBy: 'incident', cascade: ['persist'], fetch: 'EAGER')]
  private $incidentServices;

  #[ORM\OneToMany(targetEntity: IncidentUpdate::class, mappedBy: 'incident', cascade: ['persist'], fetch: 'EAGER')]
  private $incidentUpdates;

  #[ORM\ManyToOne(targetEntity: IncidentType::class, inversedBy: 'incidents')]
  #[ORM\JoinColumn(nullable: false)]
  private $type;

  #[ORM\Column(type: 'integer', nullable: true)]
  private $anticipatedResolution;

  #[ORM\Column(type: 'integer', nullable: true)]
  private $deletedOn;

  #[ORM\ManyToOne(targetEntity: User::class)]
  private $deletedBy;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'incidents')]
  #[ORM\JoinColumn(nullable: false)]
  private $createdBy;

  public function __construct()
  {
    $this->incidentServices = new ArrayCollection();
    $this->incidentUpdates = new ArrayCollection();
  }

  #[ORM\PrePersist]
  #[ORM\PreUpdate]
  public function updateTimestamps()
  {
    $currentTime = time();
    $this->setUpdated($currentTime);

    if ($this->getCreated() == null)
      $this->setCreated($currentTime);
  }

  #[ORM\PrePersist]
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

  public function getVisibility(): ?bool
  {
    return $this->visibility;
  }

  public function setVisibility(bool $visibility): self
  {
    $this->visibility = $visibility;
    return $this;
  }

  public function getOccurred(): ?int
  {
    return $this->occurred;
  }

  public function setOccurred(?int $occurred): self
  {
    $this->occurred = $occurred;
    return $this;
  }

  public function getMessage(): ?string
  {
    return $this->message;
  }

  public function setMessage(?string $message): self
  {
    $this->message = $message;
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

  public function getStatus(): ?IncidentStatus
  {
    return $this->status;
  }

  public function setStatus(?IncidentStatus $status): self
  {
    $this->status = $status;
    return $this;
  }

  public function getType(): ?IncidentType
  {
    return $this->type;
  }

  public function setType(?IncidentType $type): self
  {
    $this->type = $type;
    return $this;
  }

  public function getAnticipatedResolution(): ?int
  {
    return $this->anticipatedResolution;
  }

  public function setAnticipatedResolution(?int $anticipatedResolution): self
  {
    $this->anticipatedResolution = $anticipatedResolution;
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

  /**
   * @return Collection|IncidentServices[]
   */
  public function getIncidentServices(): Collection
  {
    return $this->incidentServices;
  }

  public function addIncidentService(IncidentService $incidentService): self
  {
    if (!$this->incidentServices->contains($incidentService)) {
      $this->incidentServices[] = $incidentService;
      $incidentService->setIncident($this);
    }

    return $this;
  }

  public function removeIncidentService(IncidentService $incidentService): self
  {
    if ($this->incidentServices->contains($incidentService)) {
      $this->incidentServices->removeElement($incidentService);
      // set the owning side to null (unless already changed)
      if ($incidentService->getIncident() === $this) {
        $incidentService->setIncident(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|IncidentUpdate[]
   */
  public function getIncidentUpdates(): Collection
  {
    return $this->incidentUpdates;
  }

  public function addIncidentUpdate(IncidentUpdate $incidentUpdate): self
  {
    if (!$this->incidentUpdates->contains($incidentUpdate)) {
      $this->incidentUpdates[] = $incidentUpdate;
      $incidentUpdate->setIncident($this);
    }

    return $this;
  }

  public function removeIncidentUpdate(IncidentUpdate $incidentUpdate): self
  {
    if ($this->incidentUpdates->contains($incidentUpdate)) {
      $this->incidentUpdates->removeElement($incidentUpdate);
      // set the owning side to null (unless already changed)
      if ($incidentUpdate->getIncident() === $this) {
        $incidentUpdate->setIncident(null);
      }
    }

    return $this;
  }

  public function getServicesString()
  {
    $services = [];
    foreach ($this->incidentServices as $incidentService)
      $services[] = $incidentService->getService()->getName();

    return implode(', ', $services);
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'name' => $this->name,
      'visibility' => $this->visibility,
      'occurred' => $this->occurred,
      'anticipatedResolution' => $this->anticipatedResolution,
      'message' => $this->message,
      'created' => $this->created,
      'updated' => $this->updated,
      'statusName' => $this->status->getName(),
      'statusType' => $this->status->getType(),
      'createdBy' => $this->createdBy->getFullName(),
      'type' => $this->type->getName(),
      'services' => $this->incidentServices->toArray(),
      'updates' => $this->incidentUpdates->toArray()
    ];
  }
}
