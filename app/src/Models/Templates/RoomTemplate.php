<?php 

namespace App\Models\Templates;

use App\Models\Enums\RoomType;

class RoomTemplate{
    public int $id;
    public int $dungeon_id;
    public string $name;
    public ?string $description;
    public RoomType $type;
    public ?int $monsterId;

    public ?int $trapDamage;

    public function __construct(int $id, string $name, ?string $description, RoomType $type, ?int $trapDamage, ?int $monsterId) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->type =  $type;
        $this->trapDamage = $trapDamage;
        $this->monsterId = $monsterId;
    }

    public function RoomType(){
        return match($this->type){
            RoomType::entrace => 'entrance',
            RoomType::empty => 'empty',
            RoomType::trap => 'trap',
            RoomType::monster => 'monster',
            RoomType::exit => 'exit',
        };
    }

    public static function fromArray(array $data): RoomTemplate {
        $type = isset($data['type']) ? RoomType::from((string)$data['type']) : RoomType::empty;
        $trapDamage = null;
        if (isset($data['trap_damage'])) {
            $trapDamage = (int)$data['trap_damage'];
        } elseif (isset($data['trapDamage'])) {
            $trapDamage = (int)$data['trapDamage'];
        }

        $monsterId = null;
        if (isset($data['monster_template']) && $data['monster_template'] !== '') {
            $monsterId = (int)$data['monster_template'];
        } elseif (isset($data['monsterId']) && $data['monsterId'] !== '') {
            $monsterId = (int)$data['monsterId'];
        }

        return new RoomTemplate(
            id: isset($data['id']) ? (int)$data['id'] : 0,
            name: $data['name'] ?? 'Unknown',
            description: $data['description'] ?? null,
            type: $type,
            trapDamage: $trapDamage,
            monsterId: $monsterId
        );
    }

}
