<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SettingRepository;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $name;

  #[ORM\Column(type: 'text', nullable: true)]
  private $value;

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

  public function getValue(): ?string
  {
    return $this->value;
  }

  public function setValue(?string $value): self
  {
    $this->value = $value;
    return $this;
  }
}
