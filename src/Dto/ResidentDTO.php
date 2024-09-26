<?php

namespace App\Dto;

use App\Entity\Room;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection as Collection;

class ResidentDTO
{
    private string $slug;
    private ?string $firstName = null;
    private ?\DateTimeImmutable $birthDate = null;
    private ?string $nationality = null;
    private Room|null $room = null;
    private User|null $referent = null;
    private Collection $bases;
    private Collection $characteristics ;
    private string|null $picture;
    private DateTimeImmutable $created;
    private DateTimeImmutable $updated;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }



    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTimeImmutable|null $birthDate
     */
    public function setBirthDate(?\DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string|null
     */
    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    /**
     * @param string|null $nationality
     */
    public function setNationality(?string $nationality): void
    {
        $this->nationality = $nationality;
    }

    /**
     * @return Room|null
     */
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    /**
     * @param Room|null $room
     */
    public function setRoom(?Room $room): void
    {
        $this->room = $room;
    }

    /**
     * @return User|null
     */
    public function getReferent(): ?User
    {
        return $this->referent;
    }

    /**
     * @param User|null $referent
     */
    public function setReferent(?User $referent): void
    {
        $this->referent = $referent;
    }

    /**
     * @return Collection
     */
    public function getBases(): Collection
    {
        return $this->bases;
    }

    /**
     * @param Collection $bases
     */
    public function setBases(Collection $bases): void
    {
        $this->bases = $bases;
    }

    /**
     * @return Collection
     */
    public function getCharacteristics(): Collection
    {
        return $this->characteristics;
    }

    /**
     * @param Collection $characteristics
     */
    public function setCharacteristics(Collection $characteristics): void
    {
        $this->characteristics = $characteristics;
    }

    /**
     * @return string
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     */
    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @param DateTimeImmutable $created
     */
    public function setCreated(DateTimeImmutable $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdated(): DateTimeImmutable
    {
        return $this->updated;
    }

    /**
     * @param DateTimeImmutable $updated
     */
    public function setUpdated(DateTimeImmutable $updated): void
    {
        $this->updated = $updated;
    }


}