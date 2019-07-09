<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Service\Generator\HashIdGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\ServiceCategoryRepository")
* @ORM\Table(indexes={@ORM\Index(name="servicecategory_hashid_idx", columns={"hash_id"})})
* @ORM\HasLifecycleCallbacks
*/
class ServiceCategory implements JsonSerializable
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
  private $hint;

  /**
  * @ORM\Column(type="integer")
  */
  private $created;

  /**
  * @ORM\Column(type="integer")
  */
  private $updated;

  /**
  * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="serviceCategory", fetch="EAGER")
  */
  private $services;

  /**
  * @ORM\Column(type="boolean")
  */
  private $deletable;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $deletedOn;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $deletedBy;

  /**
   * @ORM\Column(type="boolean")
   */
  private $editable;

  public function __construct()
  {
    $this->services = new ArrayCollection();
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

  public function getHint(): ?string
  {
    return $this->hint;
  }

  public function setHint(?string $hint): self
  {
    $this->hint = $hint;
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

  public function getDeletable(): ?bool
  {
    return $this->deletable;
  }

  public function setDeletable(bool $deletable): self
  {
    $this->deletable = $deletable;
      return $this;
  }

  /**
  * @return Collection|Service[]
  */
  public function getServices(): Collection
  {
    return $this->services;
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

  public function getEditable(): ?bool
  {
    return $this->editable;
  }

  public function setEditable(bool $editable): self
  {
    $this->editable = $editable;
      return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'name' => $this->name,
      'hint' => $this->hint,
      'created' => $this->created,
      'updated' => $this->updated,
      'deletable' => $this->deletable,
      'editable' => $this->editable
    ];
  }
}
