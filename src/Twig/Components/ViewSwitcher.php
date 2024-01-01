<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ViewSwitcher
{

    public bool $trigger = false;
    public ?string $left = "";
    public ?string $right = "";

    public function render(

    ){
        return $this->render($this->left);
    }
}
