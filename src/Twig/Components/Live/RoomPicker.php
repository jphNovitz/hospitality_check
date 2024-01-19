<?php

namespace App\Twig\Components\Live;

use App\Repository\ResidentRepository;
use App\Repository\RoomRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent()]
final class RoomPicker
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $search_room = "";

    public function __construct(protected RoomRepository $repository)
    {
    }

    public function getRooms(): array
    {
        if (!empty($this->search_room)) {
            return $this->repository->findBy(['number' => $this->search_room]);
        }  else return $this->repository->findAll();
    }

    /*public function __construct(protected ResidentRepository $repository)
    {
    }

    public function getResidents(): array
    {
        if (!empty($this->search_room)) {
            return $this->repository->findBy(['room' => $this->search_room]);
        }  else return $this->repository->findAll();
    }*/

}
