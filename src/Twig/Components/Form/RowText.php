<?php

namespace App\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Form/RowText.html.twig')]
final class RowText
{
    public mixed $row;
    public ?string $label = '';
    public ?string $placeholder = 'Entrez du texte ici';
//    public function mount(mixed $row = null){
//
//    }
}
