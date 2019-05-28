<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IncidentTypeRepository")
 */
class IncidentType
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
   * @ORM\OneToMany(targetEntity="App\Entity\Incident", mappedBy="type")
   */
  private $incidents;

  public function __construct()
  {
    $this->incidents = new ArrayCollection();
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

  /**
   * @return Collection|Incident[]
   */
  public function getIncidents(): Collection
  {
    return $this->incidents;
  }

  public function addIncident(Incident $incident): self
  {
    if (!$this->incidents->contains($incident)) {
      $this->incidents[] = $incident;
      $incident->setType($this);
    }

    return $this;
  }

  public function removeIncident(Incident $incident): self
  {
    if ($this->incidents->contains($incident)) {
      $this->incidents->removeElement($incident);
      // set the owning side to null (unless already changed)
      if ($incident->getType() === $this) {
        $incident->setType(null);
      }
    }

    return $this;
  }
}
