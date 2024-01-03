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
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent(template: 'components/Live/Resident/Form/Profile.html.twig')]
final class Profile extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public bool $isSuccessful = false;
    #[LiveProp]
    public Resident $initialResidentForm;

    public string $path;

    #[LiveProp]
    public mixed $formClass;

    public string $action = 'Update';

    public function __construct(protected ResidentRepository $residentRepository, protected EntityManagerInterface $em)
    {
    }

    #[PreMount]
    public function preMount($datas): void
    {
        $this->formClass = match ($datas['TypeName']) {
            "ResidentType" => ResidentType::class,
            "BasicType" => BasicType::class
        };
    }

    public function getResident(): ?Resident
    {
        return $this->residentRepository->findOneBy(['id' => $this->initialResidentForm]);
    }

    #[LiveListener('residentUpdated')]
    public function refreshResident(): void
    {
        $this->getResident();
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm($this->formClass, $this->initialResidentForm);
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

