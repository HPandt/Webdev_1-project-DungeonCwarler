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

    public function __construct(int $id, string $name, string $description, RoomType $type, int $trapDamage, int $monsterId) {
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
        return new RoomTemplate(
            id: isset($data['id']) ? (int)$data['id'] : 0,
            name: $data['name'] ?? 'Unknown',
            description: $data['description'] ?? null,
            type: $type,
            trapDamage: (int)$data['trap_damage'],
            monsterId: (int)$data['monster_template']
        );
    }

}
