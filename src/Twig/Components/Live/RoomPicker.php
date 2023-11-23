<?php

namespace App\Twig\Components\Live;

use App\Repository\ResidentRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent()]
final class RoomPicker
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $search_room = "";

    public function __construct(protected ResidentRepository $repository)
    {
    }

    public function getResidents()
    {
        if (!empty($this->search_room)) return $this->repository->findBy(['room' => $this->search_room]);
        else return [];
    }

}
