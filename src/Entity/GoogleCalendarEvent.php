<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoogleCalendarEventRepository")
 */
class GoogleCalendarEvent
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
  private $eventId;

  /**
   * @ORM\OneToOne(targetEntity="App\Entity\Maintenance", inversedBy="googleCalendarEvent", cascade={"persist", "remove"})
   */
  private $maintenance;


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEventId(): ?string
  {
    return $this->eventId;
  }

  public function setEventId(string $eventId): self
  {
    $this->eventId = $eventId;
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
}
