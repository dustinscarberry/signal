<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WidgetRepository")
 */
class Widget implements JsonSerializable
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
  private $type;

  /**
   * @ORM\Column(type="smallint")
   */
  private $sortorder;

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $attributes;

  public function getId(): ?int
  {
    return $this->id;
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

  public function getSortorder(): ?int
  {
    return $this->sortorder;
  }

  public function setSortorder(int $sortorder): self
  {
    $this->sortorder = $sortorder;
    return $this;
  }

  public function getAttributes()
  {
    return json_decode($this->attributes);
  }

  public function setAttributes($attributes): self
  {
    if (!is_string($attributes))
      $attributes = json_encode($attributes);

    $this->attributes = $attributes;
    return $this;
  }

  public function jsonSerialize()
  {
    return [
      'id' => $this->id,
      'type' => $this->type,
      'sortorder' => $this->sortorder,
      'attributes' => $this->getAttributes()
    ];
  }
}
