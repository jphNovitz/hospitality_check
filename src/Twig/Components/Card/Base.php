<?php

namespace App\Twig\Components\Card;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Card/Resident/Base.html.twig')]
final class Base
{
    public object $infos;
}
