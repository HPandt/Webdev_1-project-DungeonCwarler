<?php

namespace App\Services\Interfaces;

use Apps\Models\ViewModels\RoomViewModel; 

interface IRoomService {
    public function getRoomById(int $roomId);
    public function getNextRoom(array $currentRoom, string $dir);
    public function markDiscovered(int $roomId);
        
}