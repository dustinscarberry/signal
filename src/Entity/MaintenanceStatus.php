<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\MaintenanceStatusRepository")
* @ORM\Table(indexes={@ORM\Index(name="maintenancestatus_hashid_idx", columns={"hash_id"})})
* @ORM\HasLifecycleCallbacks
*/
class MaintenanceStatus implements JsonSerializable
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
  * @ORM\Column(type="boolean")
  */
  private $deletable;

  /**
  * @ORM\Column(type="boolean")
  */
  private $editable;

  /**
  * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="status")
  */
  private $maintenances;

  /**
  * @ORM\Column(type="string", length=255)
  */
  private $type;

  /**
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $updated;

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
    $this->maintenances = new ArrayCollection();
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
  * @ORM\PreUpdate
  */
  public function ensureDefaults()
  {
    if ($this->deletable === null)
    $this->deletable = true;

    if ($this->editable === null)
    $this->editable = true;
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

  public function getDeletable(): ?bool
  {
    return $this->deletable;
  }

  public function setDeletable(bool $deletable): self
  {
    $this->deletable = $deletable;
    return $this;
  }

  public function getEditable(): ?bool
  {
    return $this->editable;
  }

  public function setEditable(bool $editable): self
  {
    $this->editable = $editable;
    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;
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

  /**
  * @return Collection|Maintenance[]
  */
  public function getMaintenances(): Collection
  {
    return $this->maintenances;
  }

  public function addMaintenance(Maintenance $maintenance): self
  {
    if (!$this->maintenances->contains($maintenance)) {
      $this->maintenances[] = $maintenance;
      $maintenance->setStatus($this);
    }

    return $this;
  }

  public function removeMaintenance(Maintenance $maintenance): self
  {
    if ($this->maintenances->contains($maintenance)) {
      $this->maintenances->removeElement($maintenance);
      // set the owning side to null (unless already changed)
      if ($maintenance->getStatus() === $this) {
        $maintenance->setStatus(null);
      }
    }

    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'name' => $this->name,
      'type' => $this->type,
      'created' => $this->created,
      'updated' => $this->updated,
      'deletable' => $this->deletable,
      'editable' => $this->editable
    ];
  }
}
