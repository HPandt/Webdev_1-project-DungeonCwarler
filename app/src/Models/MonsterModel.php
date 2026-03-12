<?php 

namespace App\Models;

class MonsterModel{
    public int $id;
    public string $name;
    public string $img;
     public int $hp;
     public int $currentHp;
    public int $strength;
    public int $dex;
    public int $xp_reward;

     public function __construct(int $id, string $name, ?string $img,  int $hp, int $currentHp, int $strength, int $dex, int $xp_reward) {
        $this->id = $id;
        $this->name = $name;
        $this->img = $img;
        $this->hp =  $hp;
        $this->currentHp = $currentHp;
        $this->strength = $strength;
        $this->dex = $dex;
        $this->xp_reward = $xp_reward;
    }

}
