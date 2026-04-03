<?php 

namespace App\Models\Templates;

use App\Models\Enums\CharacterClass;

class CharacterTemplate{
    public int $id;
    public string $name;
    public ?string $img;
    public CharacterClass $class;
    public int $maxHp;
    public int $strength;
    public int $dex;
    public int $luck;

    public function __construct(int $id, string $name, ?string $img, CharacterClass $class, int $maxHp, int $strength, int $dex, int $luck) {
        $this->id = $id;
        $this->name = $name;
        $this->img = $img;
        $this->class = $class;
        $this->maxHp = $maxHp;
        $this->strength = $strength;
        $this->dex = $dex;
        $this->luck = $luck;
    }

    public function classLabel(){
        return match($this->class){
            CharacterClass::Warrior => 'warrior',
            CharacterClass::Rouge => 'rouge',
            CharacterClass::Mage => 'mage',
            CharacterClass::Barbarian => 'barbarian',
        };
    }

    public static function fromArray(array $data): CharacterTemplate {
        $class = isset($data['class']) ? CharacterClass::from((string)$data['class']) : CharacterClass::Warrior;
    
        return new CharacterTemplate(
            id: isset($data['id']) ? (int)$data['id'] : 0,
            name: $data['name'] ?? 'Unknown',
            img: $data['img'] ?? null,
            class: $class,
            maxHp: (int)$data['base_hp'],
            strength: (int)$data['base_strength'],
            dex: (int)$data['base_dex'],
            luck: (int)$data['base_luck']
        );
    }

}


