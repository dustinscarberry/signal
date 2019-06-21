<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomMetricDatapointRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CustomMetricDatapoint
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

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

  public function getId(): ?int
  {
    return $this->id;
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

  public function getValue(): ?int
  {
    return $this->value;
  }

  public function setValue(int $value): self
  {
    $this->value = $value;
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
}
