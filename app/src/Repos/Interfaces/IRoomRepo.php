<?php

namespace App\Repos\Interfaces;

interface IRoomRepo{
    public function getRoomById(int $roomId);
    public function getNextRoom(array $currentRoom, string $dir);
    public function markDiscovered(int $roomId);

    public function createRoomFromTemplate(int $dungeonId, int $templateId);
        
}