<?php

namespace App\Models\Enums;

enum CharacterClass: string {
    case Warrior = 'warrior';
    case Rouge = 'rouge';
    case Mage = 'mage';
    case Barbarian = 'barbarian';
}