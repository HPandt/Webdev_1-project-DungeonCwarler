<?php 

namespace App\Services;

use App\Models\Templates\CharacterTemplate;
use App\Models\Templates\MonsterTemplate;
use App\Models\Templates\RoomsTemplate;
use App\Repos\Interfaces\IAdminRepo;
use App\Services\Interfaces\IAdminService;

class AdminService implements IAdminService {
    private IAdminRepo $adminRepo;

    public function __construct(IAdminRepo $adminRepo) {
        $this->adminRepo = $adminRepo;
    }

    // Character management
    public function createCharacterTemplate(CharacterTemplate $characterTemplate) {
        if(empty($characterTemplate->name) || empty($characterTemplate->img) || $characterTemplate->class < 0){
            throw new \InvalidArgumentException("Invalid character data");
        }
        return $this->adminRepo->createCharacterTemplate($characterTemplate);
    }

    public function updateCharacterTemplate(CharacterTemplate $characterTemplate) {
        return $this->adminRepo->updateCharacterTemplate($characterTemplate);
    }

    public function deleteCharacterTemplate(int $characterId) {
        return $this->adminRepo->deleteCharacterTemplate($characterId);
    }

    public function getAllCharacterTemplates() {
        return $this->adminRepo->getAllCharacterTemplates();
    }

    public function getCharacterTemplateById(int $characterId) {
        return $this->adminRepo->getCharacterTemplateById($characterId);
    }

    // Monster management
    public function createMonsterTemplate(MonsterTemplate $monsterTemplate) {
        return $this->adminRepo->createMonsterTemplate($monsterTemplate);
    }

    public function updateMonsterTemplate(MonsterTemplate $monsterTemplate) {
        return $this->adminRepo->updateMonsterTemplate($monsterTemplate);
    }

    public function deleteMonsterTemplate(int $monsterId) {
        return $this->adminRepo->deleteMonsterTemplate($monsterId);
    }

    public function getAllMonsterTemplates() {
        return $this->adminRepo->getAllMonsterTemplates();
    }

    public function getMonsterTemplateById(int $monsterId) {
        return $this->adminRepo->getMonsterTemplateById($monsterId);
    }

     // Room management
     public function createRoom( RoomsTemplate $roomTemplate) {
        
        if(empty($roomTemplate->name) || empty($roomTemplate->description) || $roomTemplate->type < 0){
            throw new \InvalidArgumentException("Invalid room data");
        }
        return $this->adminRepo->createRoomTemplate($roomTemplate);
     }
     public function updateRoom( RoomsTemplate $roomTemplate) {
        
        if(empty($roomTemplate->name) || empty($roomTemplate->description) || $roomTemplate->type < 0){
            throw new \InvalidArgumentException("Invalid room data");
        }
        return $this->adminRepo->updateRoomTemplate($roomTemplate);
     }
     public function deleteRoom(int $roomId) {
        return $this->adminRepo->deleteRoomTemplate($roomId);
     }
     public function getAllRooms() {
        return $this->adminRepo->getAllRoomTemplates();
     }
     public function getRoomById(int $roomId) {
        return $this->adminRepo->getRoomTemplateById($roomId);
     }

     // User management
     public function getAllUsers() {
        return $this->adminRepo->getAllUsers();
     }  
        public function getUserById(int $userId) {
            return $this->adminRepo->getUserById($userId);
        }
        public function deleteUser(int $userId) {
            return $this->adminRepo->deleteUser($userId);
        }
} 