<?php

namespace App\Entity;

use App\Service\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomMetricDatapointRepository")
 * @ORM\Table(indexes={@ORM\Index(name="custommetricdatapoint_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class CustomMetricDatapoint implements JsonSerializable
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
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $value;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\CustomMetric", inversedBy="customMetricDatapoints")
   * @ORM\JoinColumn(nullable=false)
   */
  private $metric;

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function updateTimestamps()
  {
    if ($this->getCreated() == null)
      $this->setCreated(time());
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

  public function getValue(): ?int
  {
    return $this->value;
  }

  public function setValue(int $value): self
  {
    $this->value = $value;
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

  public function getMetric(): ?CustomMetric
  {
    return $this->metric;
  }

  public function setMetric(?CustomMetric $metric): self
  {
    $this->metric = $metric;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->hashId,
      'value' => $this->value,
      'created' => $this->created,
      'metric' => $this->getMetric()->getHashId()
    ];
  }
}
