<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\Generator\HashIdGenerator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(indexes={@ORM\Index(name="user_hashid_idx", columns={"hash_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
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
   * @ORM\Column(type="string", length=180, unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="json")
   */
  private $roles = [];

  /**
   * @var string The hashed password
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $firstName;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $lastName;

  /**
   * @ORM\Column(type="integer")
   */
  private $created;

  /**
   * @ORM\Column(type="integer")
   */
  private $updated;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $deletedOn;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $deletedBy;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Incident", mappedBy="createdBy")
   */
  private $incidents;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="createdBy")
   */
  private $maintenances;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\IncidentUpdate", mappedBy="createdBy")
   */
  private $incidentUpdates;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\MaintenanceUpdate", mappedBy="createdBy")
   */
  private $maintenanceUpdates;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $apiToken;

  /**
   * @ORM\Column(type="boolean")
   */
  private $deletable;

  public function __construct()
  {
    $this->incidents = new ArrayCollection();
    $this->maintenances = new ArrayCollection();
    $this->incidentUpdates = new ArrayCollection();
    $this->maintenanceUpdates = new ArrayCollection();
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
  * @ORM\PreUpdate
  */
  public function ensureDefaults()
  {
    if ($this->deletable === null)
      $this->deletable = true;
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

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string) $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;
    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;
    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getPassword(): string
  {
    return (string) $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;
    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;
    return $this;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;
    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;
    return $this;
  }

  public function getApiToken(): ?string
  {
    return $this->apiToken;
  }

  public function setApiToken(?string $apiToken): self
  {
    $this->apiToken = $apiToken;
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

  /**
   * @see UserInterface
   */
  public function getSalt()
  {
    // not needed when using the "bcrypt" algorithm in security.yaml
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getGravatar()
  {
    $hash = md5(strtolower(trim($this->getEmail())));
    return 'https://www.gravatar.com/avatar/' . $hash . '.jpg';
  }

  public function getFullName()
  {
    return $this->getFirstName() . ' ' . $this->getLastName();
  }

  public function getApiEnabled()
  {
    return in_array('ROLE_APIUSER', $this->roles);
  }

  public function setApiEnabled($apiEnabled)
  {
    if ($apiEnabled)
    {
      if (!in_array('ROLE_APIUSER', $this->roles))
        $this->roles[] = 'ROLE_APIUSER';
    }
    else
    {
      if (in_array('ROLE_APIUSER', $this->roles))
        if ($key = array_search('ROLE_APIUSER', $this->roles))
          unset($this->roles[$key]);
    }
  }

  public function getDeletedOn(): ?int
  {
    return $this->deletedOn;
  }

  public function setDeletedOn(?int $deletedOn): self
  {
    $this->deletedOn = $deletedOn;
    return $this;
  }

  public function getDeletedBy(): ?self
  {
    return $this->deletedBy;
  }

  public function setDeletedBy(?self $deletedBy): self
  {
    $this->deletedBy = $deletedBy;
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
      $incident->setCreatedBy($this);
    }

    return $this;
  }

  public function removeIncident(Incident $incident): self
  {
    if ($this->incidents->contains($incident)) {
      $this->incidents->removeElement($incident);
      // set the owning side to null (unless already changed)
      if ($incident->getCreatedBy() === $this) {
          $incident->setCreatedBy(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|Maintenance[]
   */
  public function getMaintenances(): Collection
  {
    return $this->maintenances;
  }

  public function addMaintenance(Maintenance $maintenance): self
  {
      if (!$this->maintenances->contains($maintenance)) {
          $this->maintenances[] = $maintenance;
          $maintenance->setCreatedBy($this);
      }

      return $this;
  }

  public function removeMaintenance(Maintenance $maintenance): self
  {
    if ($this->maintenances->contains($maintenance)) {
      $this->maintenances->removeElement($maintenance);
      // set the owning side to null (unless already changed)
      if ($maintenance->getCreatedBy() === $this) {
          $maintenance->setCreatedBy(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|IncidentUpdate[]
   */
  public function getIncidentUpdates(): Collection
  {
    return $this->incidentUpdates;
  }

  public function addIncidentUpdate(IncidentUpdate $incidentUpdate): self
  {
    if (!$this->incidentUpdates->contains($incidentUpdate)) {
      $this->incidentUpdates[] = $incidentUpdate;
      $incidentUpdate->setCreatedBy($this);
    }

    return $this;
  }

  public function removeIncidentUpdate(IncidentUpdate $incidentUpdate): self
  {
    if ($this->incidentUpdates->contains($incidentUpdate)) {
      $this->incidentUpdates->removeElement($incidentUpdate);
      // set the owning side to null (unless already changed)
      if ($incidentUpdate->getCreatedBy() === $this) {
        $incidentUpdate->setCreatedBy(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|MaintenanceUpdate[]
   */
  public function getMaintenanceUpdates(): Collection
  {
    return $this->maintenanceUpdates;
  }

  public function addMaintenanceUpdate(MaintenanceUpdate $maintenanceUpdate): self
  {
    if (!$this->maintenanceUpdates->contains($maintenanceUpdate)) {
      $this->maintenanceUpdates[] = $maintenanceUpdate;
      $maintenanceUpdate->setCreatedBy($this);
    }

    return $this;
  }

  public function removeMaintenanceUpdate(MaintenanceUpdate $maintenanceUpdate): self
  {
    if ($this->maintenanceUpdates->contains($maintenanceUpdate)) {
      $this->maintenanceUpdates->removeElement($maintenanceUpdate);
      // set the owning side to null (unless already changed)
      if ($maintenanceUpdate->getCreatedBy() === $this) {
          $maintenanceUpdate->setCreatedBy(null);
      }
    }

    return $this;
  }

  public function getDeletable(): ?bool
  {
      return $this->deletable;
  }

  public function setDeletable(bool $deletable): self
  {
      $this->deletable = $deletable;

      return $this;
  }
}
