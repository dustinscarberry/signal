<?php

namespace App\Entity;

use App\Service\Generator\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity(repositoryClass="App\Repository\IncidentServiceRepository")
* @ORM\Table(indexes={@ORM\Index(name="incidentservice_hashid_idx", columns={"hash_id"})})
* @ORM\HasLifecycleCallbacks
*/
class IncidentService implements JsonSerializable
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
  * @ORM\Column(type="integer")
  */
  private $created;

  /**
  * @ORM\Column(type="integer")
  */
  private $updated;

  /**
  * @ORM\ManyToOne(targetEntity="App\Entity\Incident", inversedBy="incidentServices")
  * @ORM\JoinColumn(nullable=false)
  */
  private $incident;

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

  public function getIncident(): ?Incident
  {
    return $this->incident;
  }

  public function setIncident(?Incident $incident): self
  {
    $this->incident = $incident;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'serviceName' => $this->service->getName(),
      'statusName' => $this->status->getName(),
      'created' => $this->created,
      'updated' => $this->updated
    ];
  }
}
