<?php

namespace App\Controllers;

use App\Models\Templates\CharacterTemplate;
use App\Exceptions\ResourceNotFoundException;
use App\Repos\AdminRepo;
use App\Repos\GameRepo;
use App\Repos\RoomRepo;
use App\Services\GameService;
use App\Services\AdminService;
use App\Models\ViewModels\CharacterViewModel;
use App\Services\RoomService;

class GameController
{
    private GameService $gameService;
    private AdminService $adminService;
    private RoomService $roomService;

    public function __construct()
    {

        $this->gameService = new GameService();
        $this->roomService = new RoomService();
        $this->adminService = new AdminService();
    }

    // Controller methods to handle game actions
    // Need to rework to fit api structure. for this and future controllers.
    public function gameDashboard()
    {
        require(__DIR__ . '/../Views/Game/index.php');
    }

    public function startMenu()
    {
        $characters = $this->adminService->getAllCharacterTemplates();
        require(__DIR__ . '/../Views/Game/start_screen.php');
    }
    public function startGame()
    {
        try {
            $userId     = $_SESSION['user_id'] ?? null;
            $templateId = $_POST['character_id'] ?? null;
            if (!$templateId) {
                header('Location: /game/dashboard');
                exit();
            }
            //Store character for the whole session

            //generate new dungeon 
            $start = $this->gameService->startGameFromTemplate($userId, $templateId);

            $_SESSION['character_id'] = $start->character->id;
            $_SESSION['dungeon_id'] = $start->dungeonId;
            $_SESSION['current_room_id'] = $start->room['id'];

            header('Location: /game/dungeon');
            exit();
        } catch (ResourceNotFoundException $e) {
            error_log('Game could not load:' . $e->getMessage());
        }
    }

    /**
     * 
     * this shows the game 
     * @return void
     */
    public function showDungeon()
    {
        try {
            $dungeonId = $_SESSION['dungeon_id'] ?? null;
            $characterId = $_SESSION['character_id'] ?? null;

            if (!$dungeonId || !$characterId) {
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
        } catch (ResourceNotFoundException $e) {
            error_log('Could not display dungeon:' . $e->getMessage());
        }
    }

    /**
     * Summary of startDungeon
     * This is the end point that starts the game log 
     * @return never
     */
    public function startDungeon()
    {
        header('Content-Type: application/json');
        $dungeonId = $_SESSION['dungeon_id'] ?? null;
        $characterId = $_SESSION['character_id'] ?? null;
        if (!$dungeonId) {
            echo json_encode(['error' => 'Dungeon not found']);
            exit;
        }

        if (!$characterId) {
            echo json_encode(['error' => 'Character not found']);
            exit;
        }

        $room = $this->gameService->getCurrentRoom($dungeonId);
        if (!$room) {
            echo json_encode(['error' => 'Current room not found']);
            exit;
        }

        $response = $this->gameService->buildRoomLogResponse($room['id']);
        echo json_encode($response);
        exit;
    }

    public function chooseDirection()
    {
        try {
            $dungeonId = $_SESSION['dungeon_id'] ?? null;
        //$data = json_decode(file_get_contents("php://input"), true);
        //$direction = $data['direction'] ?? null;
        $direction = $_POST['direction'] ?? null;
        header('Content-Type: application/json');

        if (!$dungeonId || !$direction) {
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
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()]);
        }
        
    }

    public function attack()
    {
        header('Content-Type: application/json');
        try {
            $roomId = $_SESSION['current_room_id'] ?? null;
            $characterId = $_SESSION['character_id'] ?? null;
            error_log("DEBUG: roomId=$roomId, characterId=$characterId");

            if (!$roomId || !$characterId) {
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
            echo json_encode(['error' => 'An error occurred during combat: ' . $e->getMessage()]);
            exit;
        } catch (\Throwable $err) {
            error_log("ERROR in attack(): " . $err->getMessage());
            echo json_encode(['error' => 'A critical error occurred during combat: ' . $err->getMessage()]);
            exit;
        }
    }
}
