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

            // inject runtime HP
            $monster->currentHp = $room['monster_current_hp'];
        }
        require __DIR__ . '/../Views/Game/dungeonpage.php';
    }

    public function startDungeon(){
        $dungeonId = $_SESSION['dungeon_id'] ?? null;

        if(!$dungeonId){
            echo json_encode(['error' => 'Dungeon not found']);
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
        $data = json_decode(file_get_contents("php://input"), true);
        $direction = $data['direction'] ?? null;

        if(!$dungeonId || !$direction){
            header('Location: /game/dungeon');
            exit;
        }

        $room = $this->gameService->chooseDirection($dungeonId, $direction);
        $response = $this->gameService->buildRoomLogResponse($room->id);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function attack() {
        $roomId = $_SESSION['current_room_id'] ?? null;
        $characterId = $_SESSION['character_id'] ?? null;

        if(!$roomId || !$characterId){
            header('Location: /game/dungeon');
            echo json_encode(['error' => 'Session expired']);
            exit;
        }

        $combatResult = $this->gameService->attackMonster($characterId, $roomId);
        header('Content-Type: application/json');
        echo json_encode($combatResult);
        exit;
    }

    // public function showCurrentRoom() {
    //     $dungeonId = $_SESSION['dungeon_id'] ?? null;
    //     if ($dungeonId) {
    //         $currentRoomId = $this->gameService->getCurrentRoomId($dungeonId);
    //         $room = $this->roomService->getRoomById($currentRoomId);
    //         require(__DIR__ . '/../Views/Game/room.php');
    //     } else {
    //         header('Location: /start-game');
    //         exit();
    //     }
    // }
}