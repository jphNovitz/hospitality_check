<?php

namespace App\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Form/Date.html.twig')]
final class Date
{
    public mixed $row;
    public ?string $label = '';
}
