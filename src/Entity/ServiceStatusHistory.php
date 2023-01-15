<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ServiceStatusHistoryRepository;

#[ORM\Entity(repositoryClass: ServiceStatusHistoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ServiceStatusHistory
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'serviceStatusHistories')]
  #[ORM\JoinColumn(nullable: false)]
  private $service;

  #[ORM\ManyToOne(targetEntity: ServiceStatus::class)]
  #[ORM\JoinColumn(nullable: false)]
  private $status;

  #[ORM\Column(type: 'integer')]
  private $created;

  #[ORM\PrePersist]
  #[ORM\PreUpdate]
  public function updateTimestamps()
  {
    $currentTime = time();

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

  public function getCreated(): ?int
  {
    return $this->created;
  }

  public function setCreated(int $created): self
  {
    $this->created = $created;
    return $this;
  }
}
