<?php

namespace App\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Form/ImageField.html.twig')]
final class ImageField
{
    public mixed $row;
    public ?string $label = '';
}
