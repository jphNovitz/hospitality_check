<?php

namespace App\Twig\Components\Live\Resident\Form;

use App\Entity\Resident;
use App\Form\Resident\BasicType;
use App\Form\ResidentType;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/Live/Resident/Form/Profile.html.twig')]
final class Profile extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public bool $isSuccessful = false;
    #[LiveProp]
    public ?int $residentId = null;

    public string $action = 'Update';

    public function __construct(protected ResidentRepository $residentRepository, protected  EntityManagerInterface $em){
    }

    public function getResident(): ?Resident
    {
            return $this->residentRepository->findOneBy(['id'=>$this->residentId]);
    }

    #[LiveListener('residentUpdated')]
    public function refreshResident(): void
    {
        $this->getResident();
    }
    protected function instantiateForm(): FormInterface
    {
//        dd($this->getResident());

        return $this->createForm(
            ResidentType::class,
            $this->getResident()
        );
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }

    #[NoReturn]
    #[LiveAction]
    public function save(): void
    {
        $this->submitForm();
        $this->em->flush();
        $this->emit('residentUpdated');

        $this->isSuccessful = true;
    }
}

