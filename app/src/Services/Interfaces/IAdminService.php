<?php

namespace App\Services\Interfaces;
use App\Models\Templates\CharacterTemplate;
use App\Models\Templates\MonsterTemplate;
use App\Models\Templates\RoomsTemplate;

interface IAdminService {

    // Character management
    public function createCharacterTemplate(CharacterTemplate $characterTemplate);
    public function updateCharacterTemplate(CharacterTemplate $characterTemplate);
    public function deleteCharacterTemplate(int $characterId);
    public function getAllCharacterTemplates();
    public function getCharacterTemplateById(int $characterId);


    // Monster management
    public function createMonsterTemplate(MonsterTemplate $monsterTemplate);
    public function updateMonsterTemplate(MonsterTemplate $monsterTemplate);
    public function deleteMonsterTemplate(int $monsterId);
    public function getAllMonsterTemplates();
    public function getMonsterTemplateById(int $monsterId);

    // Room management
    public function createRoom( RoomsTemplate $roomTemplate);
    public function updateRoom( RoomsTemplate $roomTemplate);
    public function deleteRoom(int $roomId);
    public function getAllRooms();
    public function getRoomById(int $roomId);

    // User management
    public function getAllUsers();
    public function getUserById(int $userId);
    public function deleteUser(int $userId);
}