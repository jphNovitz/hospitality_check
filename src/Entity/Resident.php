<?php

namespace App\Entity;

use App\Repository\ResidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResidentRepository::class)]
class Resident
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nationality = null;

    #[ORM\ManyToOne(inversedBy: 'residents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'referees')]
    private ?User $referent = null;

    #[ORM\ManyToMany(targetEntity: Base::class, mappedBy: 'resident')]
    private Collection $bases;

    #[ORM\OneToMany(mappedBy: 'resident', targetEntity: Interest::class, cascade: ["persist", "refresh"], orphanRemoval: true)]
    private Collection $interests;

    #[ORM\OneToMany(mappedBy: 'resident', targetEntity: Characteristic::class, cascade: ["persist", "refresh"], orphanRemoval: true)]
    private Collection $characteristics;

    public function __construct()
    {
        $this->bases = new ArrayCollection();
        $this->interests = new ArrayCollection();
        $this->characteristics = new ArrayCollection();
    }
    public function __toString(): string
    {
        return  $this->firstName;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getRoom(): ?room
    {
        return $this->room;
    }

    public function setRoom(?room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getReferent(): ?User
    {
        return $this->referent;
    }

    public function setReferent(?User $referent): static
    {
        $this->referent = $referent;

        return $this;
    }


    /**
     * @return Collection<int, Interest>
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Interest $interest): static
    {
        if (!$this->interests->contains($interest)) {
            $this->interests->add($interest);
            $interest->setResident($this);
        }

        return $this;
    }

    public function removeInterest(Interest $interest): static
    {
        if ($this->interests->removeElement($interest)) {
            // set the owning side to null (unless already changed)
            if ($interest->getResident() === $this) {
                $interest->setResident(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Base>
     */
    public function getBases(): Collection
    {
        return $this->bases;
    }

    public function addBasis(Base $basis): static
    {
        if (!$this->bases->contains($basis)) {
            $this->bases->add($basis);
            $basis->addResident($this);
        }

        return $this;
    }

    public function removeBasis(Base $basis): static
    {
        if ($this->bases->removeElement($basis)) {
            $basis->removeResident($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Characteristic>
     */
    public function getCharacteristics(): Collection
    {
        return $this->characteristics;
    }

    public function addCharacteristic(Characteristic $characteristic): static
    {
        if (!$this->characteristics->contains($characteristic)) {
            $this->characteristics->add($characteristic);
            $characteristic->setResident($this);
        }

        return $this;
    }

    public function removeCharacteristic(Characteristic $characteristic): static
    {
        if ($this->characteristics->removeElement($characteristic)) {
            // set the owning side to null (unless already changed)
            if ($characteristic->getResident() === $this) {
                $characteristic->setResident(null);
            }
        }

        return $this;
    }
}
