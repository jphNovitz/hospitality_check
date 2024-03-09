<?php

namespace App\Dto;

use App\Repository\RoomRepository;

class Room
{
    public function __construct(private RoomRepository $repository)
    {
    }

    public function getAll(): ?array
    {
        return $this->repository->findNumbers();
    }

    public function all(): ?array
    {
        return $this->getAll();
    }


}