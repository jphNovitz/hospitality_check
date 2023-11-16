<?php

namespace App\Dto;

use App\Repository\RoomRepository;

class Room
{
    public $all;

    public function __construct(RoomRepository $repository)
    {
        if (!empty($all_rooms = $repository->findNumbers())) $this->all = $all_rooms;
        else $this->all = [];
//        $this->infos = [];
//        $this->infos = $this->getNumbersOnly($repository->findAll());
//        dd($repository->findNumbers());
    }


}