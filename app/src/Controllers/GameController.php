<?php

namespace App\Controllers;

use App\Models\Templates\CharacterTemplate;
use App\Repos\AdminRepo;
use App\Repos\GameRepo;
use App\Repos\RoomRepo;
use App\Services\GameService;
use App\Services\AdminService;
use App\Models\ViewModels\CharacterViewModel;
use App\Services\RoomService;

class GameController {
    private GameService $gameService;
    private AdminService $adminService;
    private RoomService $roomService;

    public function __construct() {
        $gameRepo = new GameRepo();
        $roomRepo = new RoomRepo();
        $adminRepo = new AdminRepo();

        $this->gameService = new GameService($gameRepo, $roomRepo);
        $this->roomService = new RoomService($roomRepo);
        $this->adminService = new AdminService($adminRepo);
    }

    // Controller methods to handle game actions
    // Need to rework to fit api structure. for this and future controllers.
    public function gameDashboard() {
        require(__DIR__ . '/../Views/Game/index.php');
    }

    public function startMenu() {
        $characters = $this->adminService->getAllCharacterTemplates();
        require(__DIR__ . '/../Views/Game/start_screen.php');
    }
    public function startGame() {

        $userId     = $_SESSION['user_id'] ?? null;
        $templateId = $_POST['character_id'] ?? null;
        if(!$templateId){
            header('Location: /game/dashboard');
            exit();
        }
        //Store character for the whole session
        
        //generate new dungeon 
        $start = $this->gameService->startGameFromTemplate($userId, $templateId);
        // var_dump($start);
        // die();
        $_SESSION['character_id'] = $start->character->id;
        $_SESSION['dungeon_id'] = $start->dungeonId;
        $_SESSION['current_room_id'] = $start->room['id'];

        header('Location: /game/dungeon');
        exit();
    }

    public function showDungeon() {
        $dungeonId = $_SESSION['dungeon_id'] ?? null;
        $characterId = $_SESSION['character_id'] ?? null;

        if(!$dungeonId || !$characterId){
            header('Location: /game/dashboard');
            exit;
        }

        $dungeon = $this->gameService->getDungeonById($dungeonId);
        $character = $this->gameService->getCharacterById($characterId);
        $room = $this->gameService->getCurrentRoom($dungeonId);
        $_SESSION['current_room_id'] = $room['id'];
        $monster = null;
        if ($room['type'] === 'monster') {
            $monster = $this->gameService->getMonsterForRoom($room['id']);
        }
        require __DIR__ . '/../Views/Game/dungeonpage.php';
    }

    public function startDungeon(){
        $dungeonId = $_SESSION['dungeon_id'] ?? null;
        $characterId = $_SESSION['character_id'] ?? null;
        if(!$dungeonId){
            echo json_encode(['error' => 'Dungeon not found']);
            exit;
        }

        if(!$characterId){
            echo json_encode(['error' => 'Character not found']);
            exit;
        }
        $room = $this->gameService->getCurrentRoom($dungeonId);
        $response = $this->gameService->buildRoomLogResponse($room['id']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function chooseDirection() {
        $dungeonId = $_SESSION['dungeon_id'] ?? null;
        //$data = json_decode(file_get_contents("php://input"), true);
        //$direction = $data['direction'] ?? null;
        $direction = $_POST['direction'] ?? null;
        header('Content-Type: application/json');

        if(!$dungeonId || !$direction){
            echo json_encode(['error' => 'Invalid move']);
            return;
        }

        $room = $this->gameService->chooseDirection($dungeonId, $direction);
        if (!$room['success']) {
            echo json_encode(['error' => $room['message']]);
            return;
        }   

        $_SESSION['current_room_id'] = $room['roomId'];
        $_SESSION['character_id'] = $_SESSION['character_id'] ?? null;

        $response = $this->gameService->buildRoomLogResponse($room['roomId']);
        echo json_encode($response);
    }

    public function attack() {
        header('Content-Type: application/json');
       try {
         $roomId = $_SESSION['current_room_id'] ?? null;
        $characterId = $_SESSION['character_id'] ?? null;
        error_log("DEBUG: roomId=$roomId, characterId=$characterId");

        if(!$roomId || !$characterId){
            echo json_encode(['error' => 'Session expired']);
            exit;
        }

        $combatResult = $this->gameService->attackMonster($characterId, $roomId);
       $jsonResult = json_encode($combatResult);
       if ($jsonResult === false) {
           echo json_encode(['error' => 'Failed to encode combat result']);
           exit;
        }

        echo $jsonResult;
        exit;
       } catch (\Exception $e) {
           error_log("ERROR in attack(): " . $e->getMessage());
           echo json_encode(['error' => 'An error occurred during combat']);
           exit;
       }
    }
}