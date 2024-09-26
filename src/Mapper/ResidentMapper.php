<?php

namespace App\Mapper;

use App\Dto\ResidentDTO;
use App\Entity\Resident;

class ResidentMapper
{
    public function toDto(Resident $resident): ResidentDTO
    {
        $dto = new ResidentDTO();
        $dto->setSlug($resident->getSlug());
        $dto->setFirstName($resident->getFirstName());
        $dto->setBirthDate($resident->getBirthDate());

        $dto->setNationality($resident->getNationality());
        $dto->setRoom($resident->getRoom());
        $dto->setReferent($resident->getReferent());
        $dto->setBases($resident->getBases());
        $dto->setCharacteristics($resident->getCharacteristics());
        $dto->setPicture($resident->getPicture());
        $dto->setCreated($resident->getCreated());
        $dto->setUpdated($resident->getUpdated());

        return $dto;
    }

    public function toEntity(ResidentDTO $dto, ?Resident $resident = null): Resident
    {
        $resident = $resident ?? new Resident();
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

        $resident->setPicture($dto->getPicture());
        $resident->setCreated($dto->getCreated());
        $resident->setUpdated($dto->getUpdated());

        return $resident;
    }
}