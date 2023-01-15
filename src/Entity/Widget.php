<?php

namespace App\Entity;

use App\Service\Generator\HashIdGenerator;
use App\Repository\WidgetRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: WidgetRepository::class)]
#[ORM\Index(name: 'widget_hashid_idx', columns: ['hash_id'])]
#[ORM\HasLifecycleCallbacks]
class Widget implements JsonSerializable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 25, unique: true)]
  private $hashId;

  #[ORM\Column(type: 'string', length: 255)]
  private $type;

  #[ORM\Column(type: 'smallint')]
  private $sortorder;

  #[ORM\Column(type: 'text', nullable: true)]
  private $attributes;

  #[ORM\PrePersist]
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
      'id' => $this->hashId,
      'type' => $this->type,
      'sortorder' => $this->sortorder,
      'attributes' => $this->getAttributes()
    ];
  }
}
