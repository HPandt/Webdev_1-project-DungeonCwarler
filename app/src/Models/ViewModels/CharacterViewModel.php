<?php

namespace App\Models\ViewModels;

use App\Models\CharacterModel;

class CharacterViewModel{
    /**
     * @var CharacterModel[]
     */

    public array $characters;

    public function __construct(array $characters = []){
        $this->characters = $characters;
    }

}