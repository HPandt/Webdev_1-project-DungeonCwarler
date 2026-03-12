<?php 

namespace App\Models;

class RoomsModel{
    public int $id;
    public int $dungeon_id;
    public ?string $description;
    public string $type;
    public ?int $northroom;
    public ?int $southroom;
    public ?int $eastroom;
    public ?int $westroom;
    public ?int $monsterId;
    public ?int $current_hp;
    public ?int $trapDamage;
    public bool $discovered;

}
