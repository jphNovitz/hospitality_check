<?php

namespace App\Twig\Components\Live\Resident\Show;

use App\Entity\Resident;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Profile
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?int $residentId = null;

    #[LiveAction]
    public function __construct(protected ResidentRepository $residentRepository, protected  EntityManagerInterface $em){
    }

    #[LiveListener('residentUpdated')]
    public function getResident(): ?Resident
    {
        return $this->residentRepository->findOneBy(['id'=>$this->residentId]);
    }
}
