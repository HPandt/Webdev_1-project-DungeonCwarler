<?php 

namespace App\Models;

use App\Models\Enums\CharacterClass;

class CharacterModel{
    public int $id;
     public ?int $user_id;
    public string $name;
    public ?string $img;
    public CharacterClass $class;
    public int $level;
    public int $hp;
    public int $maxHp;
    public int $strength;
    public int $dex;
    public int $luck;
    public int $xp;

    public function __construct(int $id, ?int $user_id, string $name, ?string $img, CharacterClass $class, int $level, int $hp, int $maxHp, int $strength, int $dex, int $luck, int $xp) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->name = $name;
        $this->img = $img;
        $this->class = $class;
        $this->level = $level;
        $this->hp = $hp;
        $this->maxHp = $maxHp;
        $this->strength = $strength;
        $this->dex = $dex;
        $this->luck = $luck;
        $this->xp = $xp;
    }

    public function classLabel(){
        return match($this->class){
            CharacterClass::Warrior => 'warrior',
            CharacterClass::Rouge => 'rouge',
            CharacterClass::Mage => 'mage',
            CharacterClass::Barbarian => 'barbarian',
        };
    }

}


