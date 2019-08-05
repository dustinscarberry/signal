<?php

namespace App\Entity;

use App\Service\Generator\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\MaintenanceUpdateRepository")
* @ORM\Table(indexes={@ORM\Index(name="maintenanceupdate_hashid_idx", columns={"hash_id"})})
* @ORM\HasLifecycleCallbacks
*/
class MaintenanceUpdate implements JsonSerializable
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
  * @ORM\Column(type="text")
  */
  private $message;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\Maintenance", inversedBy="maintenanceUpdates")
  * @ORM\JoinColumn(nullable=false)
  */
  private $maintenance;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\MaintenanceStatus")
  * @ORM\JoinColumn(nullable=false)
  */
  private $status;

  /**
  * @ORM\Column(type="integer")
  */
  private $created;

  /**
  * @ORM\Column(type="integer")
  */
  private $updated;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="maintenanceUpdates")
   * @ORM\JoinColumn(nullable=false)
   */
  private $createdBy;

  /**
  * @ORM\PrePersist
  * @ORM\PreUpdate
  */
  public function updateTimestamps()
  {
    $currentTime = time();
    $this->setUpdated($currentTime);

    if ($this->getCreated() === null)
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

  public function getMessage(): ?string
  {
    return $this->message;
  }

  public function setMessage(string $message): self
  {
    $this->message = $message;
    return $this;
  }

  public function getMaintenance(): ?Maintenance
  {
    return $this->maintenance;
  }

  public function setMaintenance(?Maintenance $maintenance): self
  {
    $this->maintenance = $maintenance;
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

  public function getCreatedBy(): ?User
  {
    return $this->createdBy;
  }

  public function setCreatedBy(?User $createdBy): self
  {
    $this->createdBy = $createdBy;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'message' => $this->message,
      'statusName' => $this->status->getName(),
      'statusType' => $this->status->getType(),
      'created' => $this->created,
      'updated' => $this->updated
    ];
  }
}
