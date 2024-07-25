<?php

namespace App\Twig\Components\Live\Resident;

use App\Repository\ResidentRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent()]
final class ResidentPicker
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = "";

    public function __construct(protected ResidentRepository $repository)
    {
    }

    public function getResidents(): array
    {
        if (!empty($this->query))
            return $this->repository->findByName($this->query);
        else return $this->repository->findAll();
    }

}
