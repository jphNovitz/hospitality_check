<?php

namespace App\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class RowEmail
{
    public mixed $row;
    public ?string $label = '';
    public ?string $placeholder = 'Entrez une adresse email';
}
