<?php

namespace App\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Form/RowSelect.html.twig')]
final class RowSelect
{
    public mixed $row;
    public ?string $label = '';
}
