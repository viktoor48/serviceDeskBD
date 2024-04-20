<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TypeRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRequestRepository::class)]
#[ApiResource]
class TypeRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'typeRequest')]
    private Collection $request;

    public function __construct()
    {
        $this->request = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequest(): Collection
    {
        return $this->request;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->request->contains($request)) {
            $this->request->add($request);
            $request->setTypeRequest($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->request->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getTypeRequest() === $this) {
                $request->setTypeRequest(null);
            }
        }

        return $this;
    }
}
