<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ApiResource]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $timeStart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $timeClose = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $decription = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cabinet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numberBuilding = null;

    #[ORM\ManyToOne(inversedBy: 'request')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'request')]
    private ?TypeRequest $typeRequest = null;

    /**
     * @var Collection<int, Device>
     */
    #[ORM\ManyToMany(targetEntity: Device::class, inversedBy: 'requests')]
    private Collection $device;

    /**
     * @var Collection<int, Worker>
     */
    #[ORM\ManyToMany(targetEntity: Worker::class, mappedBy: 'request')]
    private Collection $workers;

    public function __construct()
    {
        $this->device = new ArrayCollection();
        $this->workers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeStart(): ?string
    {
        return $this->timeStart;
    }

    public function setTimeStart(string $timeStart): static
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    public function getTimeClose(): ?string
    {
        return $this->timeClose;
    }

    public function setTimeClose(?string $timeClose): static
    {
        $this->timeClose = $timeClose;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(?string $decription): static
    {
        $this->decription = $decription;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCabinet(): ?string
    {
        return $this->cabinet;
    }

    public function setCabinet(?string $cabinet): static
    {
        $this->cabinet = $cabinet;

        return $this;
    }

    public function getNumberBuilding(): ?string
    {
        return $this->numberBuilding;
    }

    public function setNumberBuilding(?string $numberBuilding): static
    {
        $this->numberBuilding = $numberBuilding;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getTypeRequest(): ?TypeRequest
    {
        return $this->typeRequest;
    }

    public function setTypeRequest(?TypeRequest $typeRequest): static
    {
        $this->typeRequest = $typeRequest;

        return $this;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevice(): Collection
    {
        return $this->device;
    }

    public function addDevice(Device $device): static
    {
        if (!$this->device->contains($device)) {
            $this->device->add($device);
        }

        return $this;
    }

    public function removeDevice(Device $device): static
    {
        $this->device->removeElement($device);

        return $this;
    }

    /**
     * @return Collection<int, Worker>
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    public function addWorker(Worker $worker): static
    {
        if (!$this->workers->contains($worker)) {
            $this->workers->add($worker);
            $worker->addRequest($this);
        }

        return $this;
    }

    public function removeWorker(Worker $worker): static
    {
        if ($this->workers->removeElement($worker)) {
            $worker->removeRequest($this);
        }

        return $this;
    }
}
