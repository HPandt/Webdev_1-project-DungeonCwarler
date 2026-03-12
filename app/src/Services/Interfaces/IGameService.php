<?php

namespace App\Services\Interfaces;

use Apps\Models\ViewModels\RoomsViewModel; 
use Apps\Models\ViewModels\DungeonViewModel;


interface IGameService {

  /* ===== Game Start ===== */

    public function startGameFromTemplate(int $userId, int $templateId);

    public function getCharacterById(int $characterId);

    /* ===== Movement ===== */

    public function chooseDirection(int $dungeonId, string $direction);

    public function getCurrentRoom(int $dungeonId);

    public function getCurrentRoomId(int $dungeonId);

    public function updateCurrentRoom(int $dungeonId, int $roomId);

    public function buildRoomLogResponse(int $roomId);

    /* ===== Monster ===== */

    public function getMonsterForRoom(int $roomId);

    /* ===== Combat ===== */
    public function attackMonster(int $characterId, int $roomId);

    /* ===== Dungeon ===== */

    public function generateDungeon(int $characterId, int $startingRoomId = 30);

    public function getDungeonById(int $dungeonId);

    public function randomizeRooms(array $rooms);
}
