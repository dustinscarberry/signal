<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\MaintenanceServiceRepository")
* @ORM\HasLifecycleCallbacks
*/
class MaintenanceService implements JsonSerializable
{
  /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
  private $id;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\Service")
  * @ORM\JoinColumn(nullable=false)
  */
  private $service;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\ServiceStatus")
  * @ORM\JoinColumn(nullable=false)
  */
  private $status;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\Maintenance", inversedBy="maintenanceServices")
  * @ORM\JoinColumn(nullable=false)
  */
  private $maintenance;

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

  public function getId(): ?int
  {
    return $this->id;
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

  public function getStatus(): ?ServiceStatus
  {
    return $this->status;
  }

  public function setStatus(?ServiceStatus $status): self
  {
    $this->status = $status;
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

  public function jsonSerialize()
  {
    return [
      'id' => $this->id,
      'serviceName' => $this->service->getName(),
      'statusName' => $this->status->getName(),
      'created' => $this->created,
      'updated' => $this->updated
    ];
  }
}
