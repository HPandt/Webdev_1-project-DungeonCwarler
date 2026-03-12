<?php 

namespace App\Models\Templates;

class MonsterTemplate{
    public int $id;
    public string $name;
    public string $img;
     public int $hp;
    public int $strength;
    public int $dex;
    public int $xp_reward;

     public function __construct(int $id, string $name, ?string $img,  int $hp, int $strength, int $dex, int $xp_reward) {
        $this->id = $id;
        $this->name = $name;
        $this->img = $img;
        $this->hp =  $hp;
        $this->strength = $strength;
        $this->dex = $dex;
        $this->xp_reward = $xp_reward;
    }

}
