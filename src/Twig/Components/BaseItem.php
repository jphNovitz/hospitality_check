<?php

namespace App\Twig\Components;

use App\Entity\Base;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class BaseItem
{

    public  Base $base;


}
