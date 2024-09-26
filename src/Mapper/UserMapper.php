<?php

namespace App\Mapper;

use App\Entity\User;
use App\Dto\UserDTO;

class UserMapper
{
    public function toDTO(User $user): UserDTO
    {
        $dto = new UserDTO();
        $dto->setName($user->getName());
        $dto->setEmail($user->getEmail());
        $dto->setRoles($user->getRoles());
        $dto->setPassword($user->getPassword());
        $dto->setIsVerified($user->isVerified());
        $dto->setReferees($user->getReferees());

        return $dto;
    }


    public function toEntity(UserDTO $dto, ?User $user = null): User
    {
        $user = $user ?? new User();
        $user->setName($dto->getName());
        $user->setEmail($dto->getEmail());
        $user->setRoles($dto->getRoles());
        $user->setPassword($dto->getPassword());
        $user->setIsVerified($dto->isVerified());

        // Optionnel: nettoyer les referees existants si nécessaire
        foreach ($user->getReferees() as $referee) {
            $user->removeReferee($referee);
        }
        foreach ($dto->getReferees() as $referee) {
                $user->addReferee($referee);
        }
        return $user;
    }
}