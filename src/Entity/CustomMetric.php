<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomMetricRepository")
 * @ORM\Table(indexes={@ORM\Index(name="custommetric_guid_idx", columns={"guid"})})
 * @ORM\HasLifecycleCallbacks
 */
class CustomMetric implements JsonSerializable
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $description;

  /**
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $updated;

  /**
   * @ORM\Column(type="uuid")
   */
  private $guid;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\CustomMetricDatapoint", mappedBy="metric", orphanRemoval=true)
   */
  private $customMetricDatapoints;

  /**
   * @ORM\Column(type="integer")
   */
  private $scaleStart;

  /**
   * @ORM\Column(type="integer")
   */
  private $scaleEnd;

  public function __construct()
  {
      $this->customMetricDatapoints = new ArrayCollection();
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
  public function createGuid()
  {
    if ($this->guid == null)
      $this->guid = Uuid::uuid4();
  }

  public function getId(): ?int
  {
    return $this->id;
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

  public function getGuid()
  {
    return $this->guid;
  }

  public function setGuid($guid): self
  {
    $this->guid = $guid;
    return $this;
  }

  /**
   * @return Collection|CustomMetricDatapoint[]
   */
  public function getCustomMetricDatapoints(): Collection
  {
      return $this->customMetricDatapoints;
  }

  public function addCustomMetricDatapoint(CustomMetricDatapoint $customMetricDatapoint): self
  {
      if (!$this->customMetricDatapoints->contains($customMetricDatapoint)) {
          $this->customMetricDatapoints[] = $customMetricDatapoint;
          $customMetricDatapoint->setMetric($this);
      }

      return $this;
  }

  public function removeCustomMetricDatapoint(CustomMetricDatapoint $customMetricDatapoint): self
  {
      if ($this->customMetricDatapoints->contains($customMetricDatapoint)) {
          $this->customMetricDatapoints->removeElement($customMetricDatapoint);
          // set the owning side to null (unless already changed)
          if ($customMetricDatapoint->getMetric() === $this) {
              $customMetricDatapoint->setMetric(null);
          }
      }

      return $this;
  }

  public function getScaleStart(): ?int
  {
      return $this->scaleStart;
  }

  public function setScaleStart(int $scaleStart): self
  {
      $this->scaleStart = $scaleStart;

      return $this;
  }

  public function getScaleEnd(): ?int
  {
      return $this->scaleEnd;
  }

  public function setScaleEnd(int $scaleEnd): self
  {
      $this->scaleEnd = $scaleEnd;

      return $this;
  }

  public function jsonSerialize()
  {
    return [
      'guid' => $this->guid,
      'name' => $this->name,
      'description' => $this->description,
      'created' => $this->created,
      'updated' => $this->updated,
      'scaleStart' => $this->scaleStart,
      'scaleEnd' => $this->scaleEnd
    ];
  }
}
