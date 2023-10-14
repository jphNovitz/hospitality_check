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
    private Collection $basePrefs;

    public function __construct()
    {
        $this->basePrefs = new ArrayCollection();
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
     * @return Collection<int, Base>
     */
    public function getBasePrefs(): Collection
    {
        return $this->basePrefs;
    }

    public function addBasePref(Base $basePref): static
    {
        if (!$this->basePrefs->contains($basePref)) {
            $this->basePrefs->add($basePref);
            $basePref->addResident($this);
        }

        return $this;
    }

    public function removeBasePref(Base $basePref): static
    {
        if ($this->basePrefs->removeElement($basePref)) {
            $basePref->removeResident($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return  $this->firstName;
    }
}
