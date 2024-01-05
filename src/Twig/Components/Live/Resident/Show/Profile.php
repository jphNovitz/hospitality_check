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
    public Resident $initialResident;

//    public function getResident(): ?Resident
//    {
//        return $this->residentRepository->findOneBy(['id' => $this->initialResidentForm]);
//    }

    #[LiveProp]
    public string $path;
    #[LiveAction]
    public function __construct(protected ResidentRepository $residentRepository){
    }
    public function getResident(bool $update = false): ?Resident
    {
        if(!$update) return $this->initialResident ;
        else return $this->residentRepository->findOneBy(['id' => $this->initialResident]);
    }

    #[LiveListener('residentUpdated')]
    public function refreshResident(): void
    {
        $this->getResident(true);
    }

//    #[LiveListener('residentUpdated')]
//    public function getResident(): ?Resident
//    {
//        return $this->residentRepository->findOneBy(['id'=>$this->resident->getId()]);
//    }
}
