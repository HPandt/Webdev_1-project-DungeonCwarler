<?php

namespace App\Core;

class Dice{

    public function __construct()
    {
    }
    public static function roll($sides = 20){
        return random_int(1, $sides);
    }

    public static function rollWithStats($statBonus){
        return self::roll() + $statBonus;
    }

    public static function damage(int $numDice, int $sides){
       $total = 0;

       for ($i=0; $i < $numDice; $i++) { 
        $total += random_int(1, $sides);
       }
       return $total;
    }
    
}