<?php

namespace App\Twig\Components\Card;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Card/Resident.html.twig')]
final class Resident
{
    public object $resident;
}
