<?php

namespace App\Mapper;

use App\Dto\ResidentDTO;
use App\Entity\Resident;

class ResidentMapper
{
    public function toDto(Resident $resident): ResidentDTO
    {
        $dto = new ResidentDTO();
        if ($resident->getId()) {   
            $dto->setId($resident->getId());
            $dto->setSlug($resident->getSlug());
        $dto->setCreated($resident->getCreated());
        $dto->setUpdated($resident->getUpdated());
        }

        $dto->setFirstName($resident->getFirstName());
        $dto->setBirthDate($resident->getBirthDate());

        $dto->setNationality($resident->getNationality());
        $dto->setRoom($resident->getRoom());
        $dto->setReferent($resident->getReferent());
        $dto->setBases($resident->getBases());
        $dto->setCharacteristics($resident->getCharacteristics());
        $dto->setPicture($resident->getPicture());

        return $dto;
    }

    public function toEntity(ResidentDTO $dto, ?Resident $resident = null): Resident
    {
        $resident = $resident ?? new Resident();
        $this->hydrateResident($resident, $dto);
        return $resident;
    }

    public function updateEntityFromDto(ResidentDTO $dto, Resident $resident ): Resident
    {
        $this->hydrateResident($resident, $dto);
        $resident->setUpdated($dto->getUpdated());

        return $resident;
    }

    /**
     * @param Resident $resident
     * @param ResidentDTO $dto
     * @return void
     */
    public function hydrateResident(Resident $resident, ResidentDTO $dto): void
    {
        $resident->setFirstName($dto->getFirstName());
        $resident->setBirthDate($dto->getBirthDate());
        $resident->setNationality($dto->getNationality());
        $resident->setRoom($dto->getRoom());
        $resident->setReferent($dto->getReferent());

        foreach ($resident->getBases() as $base) {
            $resident->removeBasis($base);
        }
        foreach ($dto->getBases() as $base) {
            $resident->addBasis($base);
        }
        foreach ($resident->getCharacteristics() as $Characteristic) {
            $resident->removeCharacteristic($Characteristic);
        }
        foreach ($dto->getCharacteristics() as $Characteristic) {
            $resident->addCharacteristic($Characteristic);
        }
        $resident->setImageFile($dto->getImageFile());
        $resident->setCreated($dto->getCreated());
    }
}