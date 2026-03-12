<?php 

namespace App\Models\Templates;

use App\Models\Enums\RoomType;

class RoomsTemplate{
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

}
