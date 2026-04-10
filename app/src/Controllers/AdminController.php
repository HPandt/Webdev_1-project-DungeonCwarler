<?php
namespace App\Controllers;
use App\Services\AdminService;
use App\Services\AuthService;
use App\Services\ImageService;
use App\Models\Templates\CharacterTemplate;
use App\Models\Templates\MonsterTemplate;
use App\Models\Templates\RoomTemplate;
use App\Models\Enums\CharacterClass;
use App\Models\UserModel;

class AdminController {
    private AdminService $adminService;
    private AuthService $authService;
    private ImageService $imageService;

    public function __construct() {
        $this->adminService = new AdminService();
        $this->authService = new AuthService();
        $this->imageService = new ImageService();
    }

    public function dashBoard() {
        require(__DIR__ . '/../Views/Admin/index.php');
    }

    public function showUsers() {
        $users = $this->adminService->getAllUsers();
        require(__DIR__ . '/../Views/Admin/users.php');    
    }

    public function showUserForm() {
        $user = null;
        require(__DIR__ . '/../Views/Admin/Forms/userForm.php');
    }

    public function createUser() {
        try {
            $user = UserModel::fromArray($_POST);
            $this->authService->createUser($user);
            header('Location: /admin/users');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error creating user: " . $th->getMessage());
        }
    }


    public function editUser($param) {
        $id = $param['id'] ?? null;
        $user = $this->adminService->getUserById((int)$id);
        if (!$user) {
            header('Location: /admin/users?error=User not found');
            exit();
        }
        require(__DIR__ . '/../Views/Admin/Forms/userForm.php');
    }

    public function updateUser() {
        try {
            $userId = $_POST['id'] ?? null;
            if (!$userId) {
                header('Location: /admin/users?error=Invalid user ID');
                exit();
            }
            $existingUser = $this->adminService->getUserById((int)$userId);
            if (!$existingUser) {
                header('Location: /admin/users?error=User not found');
                exit();
            }

            $user = UserModel::fromArray($_POST);
            if (empty($_POST['password'])) {
                $user->password_hash = $existingUser->password_hash;
            }

            $this->adminService->updateUser($user);
            header('Location: /admin/users');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error updating user: " . $th->getMessage());
        }    
    }

    public function deleteUser($param) {
        $id = $param['id'] ?? null;
        try {
            if(!$id){
            header('Location: /admin/users?error=Invalid user ID');
            exit();
        }
        $this->adminService->deleteUser((int)$id);
        header('Location: /admin/users');
        exit();
        } catch (\Exception $e) {
            throw new \Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public function showCharacterTemplates() {
        $characters = $this->adminService->getAllCharacterTemplates();
        require(__DIR__ . '/../Views/Admin/characters.php');    
    }

    public function showCharacterForm() {
        $character = null;
        require(__DIR__ . '/../Views/Admin/Forms/characterForm.php');
    }

    public function createCharacterTemplate() {
        try {
            $characterTemplate = CharacterTemplate::fromArray($_POST);

            if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
                $characterTemplate->img = $this->imageService->uploadTemplateImage($_FILES['img']);
            }

            $this->adminService->createCharacterTemplate($characterTemplate);
            header('Location: /admin/characters');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error creating character template: " . $th->getMessage());
        }        
    }

    public function editCharacterTemplate($param){
        $id = $param['id'] ?? null;
        $character = $this->adminService->getCharacterTemplateById((int)$id);
        if(!$character){
            header('Location: /admin/characters?error=Character not found');
            exit();
        }
        require(__DIR__ . '/../Views/Admin/Forms/characterForm.php');
    }

    public function updateCharacterTemplate() {
        try {
            $existingCharacter = $this->adminService->getCharacterTemplateById((int)($_POST['id'] ?? 0));
            if (!$existingCharacter) {
                header('Location: /admin/characters?error=Character not found');
                exit();
            }

            $characterTemplate = CharacterTemplate::fromArray($_POST);
            $characterTemplate->img = $existingCharacter->img;

            if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
                $characterTemplate->img = $this->imageService->uploadTemplateImage($_FILES['img'], $existingCharacter->img);
            }

            if (!$characterTemplate->id) {
                header('Location: /admin/characters?error=Invalid character ID');
                exit();
            }

            $this->adminService->updateCharacterTemplate($characterTemplate);
            header('Location: /admin/characters');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error updating character template: " . $th->getMessage());
        }    
    }

    public function deleteCharacterTemplate($param) {
        $id = $param['id'] ?? null;
        try {
            if(!$id){
            header('Location: /admin/characters?error=Invalid character ID');
            exit();
        }
        $this->adminService->deleteCharacterTemplate((int)$id);
        header('Location: /admin/characters');
        exit();
        } catch (\Exception $e) {
            throw new \Exception("Error deleting character template: " . $e->getMessage());
        }
    }

    public function showRoomTemplates() {
        $rooms = $this->adminService->getAllRoomTemplates();
        require(__DIR__ . '/../Views/Admin/rooms.php');    
    }

    public function showRoomForm() {
        $room = null;
        $monsters = $this->adminService->getAllMonsterTemplates();  
        require(__DIR__ . '/../Views/Admin/Forms/roomForm.php');
    }

    public function createRoom() {
        try {
            $roomTemplate = RoomTemplate::fromArray($_POST);
            $this->adminService->createRoomTemplate($roomTemplate);
            header('Location: /admin/rooms');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error creating room template: " . $th->getMessage());
        }        
    }

    public function editRoomTemplate($param){
        $id = $param['id'] ?? null;
        $room = $this->adminService->getRoomTemplateById((int)$id);
        $monsters = $this->adminService->getAllMonsterTemplates();
        if(!$room){
            header('Location: /admin/rooms?error=Room not found');
            exit();
        }
        require(__DIR__ . '/../Views/Admin/Forms/roomForm.php');
    }

    public function updateRoomTemplate() {
        try {
            $roomTemplate = RoomTemplate::fromArray($_POST);
            if(!$roomTemplate->id){
                header('Location: /admin/rooms?error=Invalid room ID');
                exit();
            }   
            $this->adminService->updateRoomTemplate($roomTemplate);
            header('Location: /admin/rooms');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error updating room template: " . $th->getMessage());
        }    
    }

    public function deleteRoomTemplate($param) {
        $id = $param['id'] ?? null;
        try {
            if(!$id){
            header('Location: /admin/rooms?error=Invalid room ID');
            exit();
        }
        $this->adminService->deleteRoomTemplate((int)$id);
        header('Location: /admin/rooms');
        exit();
        } catch (\Exception $e) {
            throw new \Exception("Error deleting room template: " . $e->getMessage());
        }
    }

    public function showMonsterTemplates() {
        $monsters = $this->adminService->getAllMonsterTemplates();
        require(__DIR__ . '/../Views/Admin/monsters.php');    
    }

    public function showMonsterForm() {
        $monster = null;
        require(__DIR__ . '/../Views/Admin/Forms/monsterForm.php');
    }

    public function createMonsterTemplate(){
        try {
            $monsterTemplate = MonsterTemplate::fromArray($_POST);

            if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
                $monsterTemplate->img = $this->imageService->uploadTemplateImage($_FILES['img']);
            }

            $this->adminService->createMonsterTemplate($monsterTemplate);
            header('Location: /admin/monsters');
            exit();
        } catch (\Throwable $th) {
            //throw $th;
            throw new \Exception("Error creating monster template: " . $th->getMessage());
        }
    }

    public function editMonsterTemplate($param){
        $id = $param['id'] ?? null;
        $monster = $this->adminService->getMonsterTemplateById((int)$id);
        if (!$monster) {
            header('Location: /admin/monsters?error=Monster not found');
            exit();
        }

        require(__DIR__ . '/../Views/Admin/Forms/monsterForm.php');
    }

    public function updateMonsterTemplate(){
        try {
            $existingMonster = $this->adminService->getMonsterTemplateById((int)($_POST['id'] ?? 0));
            if (!$existingMonster) {
                header('Location: /admin/monsters?error=Monster not found');
                exit();
            }

            $monsterTemplate = MonsterTemplate::fromArray($_POST);
            $monsterTemplate->img = $existingMonster->img;

            if (isset($_FILES['img']) && $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
                $monsterTemplate->img = $this->imageService->uploadTemplateImage($_FILES['img'], $existingMonster->img);
            }

            if(!$monsterTemplate->id){
                header('Location: /admin/monsters?error=Invalid monster ID');
                exit();
            }   
            $this->adminService->updateMonsterTemplate($monsterTemplate);
            header('Location: /admin/monsters');
            exit();
        } catch (\Throwable $th) {
            throw new \Exception("Error updating monster template: " . $th->getMessage());
        }    
    }

    public function deleteMonsterTemplate($param){
        $id = $param['id'] ?? null;
        try {
            if(!$id){
            header('Location: /admin/monsters?error=Invalid monster ID');
            exit();
            }
            $this->adminService->deleteMonsterTemplate((int)$id);
            header('Location: /admin/monsters');
            exit();
        } catch (\Exception $e) {
            throw new \Exception("Error deleting monster template: " . $e->getMessage());
        }
    }
}