<?php

namespace App\Entity;

use App\Repository\BaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[Vich\Uploadable]
class Base
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'base', fileNameProperty: 'icon', size: 'imageSize')]
    private ?File $imageFile = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icon = null;
    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;
    #[ORM\ManyToMany(targetEntity: Resident::class, inversedBy: 'bases')]
    private Collection $resident;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $created ;

    #[ORM\Column(name: 'updated', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private ?\DateTimeImmutable $updated;

    public function __construct()
    {
        $this->resident = new ArrayCollection();
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection<int, Resident>
     */
    public function getResident(): Collection
    {
        return $this->resident;
    }

    public function addResident(Resident $resident): static
    {
        if (!$this->resident->contains($resident)) {
            $this->resident->add($resident);
        }

        return $this;
    }

    public function removeResident(Resident $resident): static
    {
        $this->resident->removeElement($resident);

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeImmutable $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeImmutable $updated): static
    {
        $this->updated = $updated;

        return $this;
    }
}
