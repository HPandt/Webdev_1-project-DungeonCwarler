<?php

namespace App\Repos\Interfaces;

use Apps\Models\ViewModels\RoomsViewModel; 
use Apps\Models\ViewModels\DungeonViewModel;


interface IGameRepo {
    public function generateDungeon(int $characterId, int $startingRoomId);
    public function createFromTemplate(int $userId, int $templateId);
    public function getCharacterById(int $characterId);
    public function getMonsterForRoom(int $roomId);
    public function kill(int $id);
    public function randomizeRooms($rooms);
    public function chooseDirection(int $dungeonId, string $direction);
    public function showRoom(int $dungeonId, int $roomId);
    public function addXP(int $characterId, int $roomId);
    public function damageCharacter(int $characterId, int $damage);
    public function damageMonster(int $roomId, int $damage);
    public function clearMonsterFromRoom(int $roomId);
    public function checkIfCharacterIsAlive(int $characterId);
    public function checkIfMonsterIsAlive(int $roomId);
    public function getCurrentRoom(int $dungeonId);
    public function getDungeonById(int $dungeonId);
    public function updateCurrentRoom(int $dungeonId, int $roomId);
    public function getCurrentRoomId(int $dungeonId);
    
}