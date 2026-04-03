<?php 

namespace App\Services;

use App\Models\Enums\CharacterClass;
use App\Models\Templates\CharacterTemplate;
use App\Models\Templates\MonsterTemplate;
use App\Models\Templates\RoomTemplate;
use App\Models\UserModel;
use App\Repos\AdminRepo;
use App\Repos\Interfaces\IAdminRepo;
use App\Services\Interfaces\IAdminService;

class AdminService implements IAdminService {
    private IAdminRepo $adminRepo;

    public function __construct() {
        $this->adminRepo = new AdminRepo();
    }

    // Character management
    public function createCharacterTemplate(CharacterTemplate $characterTemplate) {
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
     public function createRoomTemplate(RoomTemplate $roomTemplate)
     {        
        if(empty($roomTemplate->name) || empty($roomTemplate->description) || $roomTemplate->type < 0){
            throw new \InvalidArgumentException("Invalid room data");
        }
        return $this->adminRepo->createRoomTemplate($roomTemplate);
     }
     public function updateRoomTemplate(RoomTemplate $roomTemplate)
     {         
        if(!$roomTemplate->id){
            throw new \InvalidArgumentException("Invalid room ID");
        }     
        if(empty($roomTemplate->name) || empty($roomTemplate->description) || $roomTemplate->type < 0){
            throw new \InvalidArgumentException("Invalid room data");
        }
        return $this->adminRepo->updateRoomTemplate($roomTemplate);
     }
     public function deleteRoomTemplate(int $roomId) {
        return $this->adminRepo->deleteRoomTemplate($roomId);
     }
     public function getAllRoomTemplates() {
        return $this->adminRepo->getAllRoomTemplates();
     }
     public function getRoomTemplateById(int $roomId) {
        return $this->adminRepo->getRoomTemplateById($roomId);
     }

     // User management
     public function getAllUsers() {
        return $this->adminRepo->getAllUsers();
     }  
        public function getUserById(int $userId) {
            return $this->adminRepo->getUserById($userId);
        }
        public function updateUser(UserModel $user): bool {
            return $this->adminRepo->updateUser($user);
        }
        public function deleteUser(int $userId) {
            return $this->adminRepo->deleteUser($userId);
        }
} 