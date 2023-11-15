<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CharacteristicExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('group', [$this, 'group_by_content_type']),
            new TwigFilter('group_form', [$this, 'form_by_content_type']),
        ];
    }

    public function group_by_content_type($characteristics): array
    {
        $filtered_characteristics = [];
        foreach ($characteristics as $characteristic) {
            $filtered_characteristics[$characteristic->getContentType()][]= $characteristic;
//            if (array_key_exists($filtered_characteristics[$characteristic->getContentType()], $filtered_characteristics)) {
//                array_push(
//                    $filtered_characteristics[$characteristic->getContentType()],
//                    $characteristic);
//            } else {
//                $filtered_characteristics[$characteristic->getContentType()][]= $characteristic;
//            }
        }
//        dd($filtered_characteristics);
//        return 'TEST';
        return  $filtered_characteristics;
    }

    public function form_by_content_type($form_characteristics): array
    {
//        $filtered_form_characteristics = [];
        $filtered_form_characteristics = [];

        foreach ($form_characteristics as $form_characteristic){
            $filtered_form_characteristics[$form_characteristic->vars['value']->getContentType()][]= $form_characteristic;
//            dd($form_characteristic->vars['value']->getContentType());
//            dd($form_characteristic->children['contentType']->getData());
        }



//        dd($filtered_form_characteristics);
        return  $filtered_form_characteristics;
    }
}
