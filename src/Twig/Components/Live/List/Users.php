<?php

namespace App\Twig\Components\Live\List;


use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class Users
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $query = 5;

    public function __construct(private readonly UserRepository $repository)
    {
//        dd($this->repository->findAlphabeticalFirsts($this->query));
    }

    public function getUsers(): array
    {
        return $this->repository->findAlphabeticalFirsts($this->query);
    }

}
