<?php 

namespace App\Services;

use App\Repos\Interfaces\IGameRepo;
use App\Services\Interfaces\IGameService;
use App\Repos\Interfaces\IRoomRepo;
use App\Core\Dice;

class GameService implements IGameService {
    private IGameRepo $gameRepository;
    private IRoomRepo $roomRepository;
    public function __construct(IGameRepo $gameRepository, IRoomRepo $roomRepository) {
        $this->gameRepository = $gameRepository;
        $this->roomRepository = $roomRepository;
    } 
    /* ===== Game Start ===== */
   
    public function startGameFromTemplate(int $userId, int $templateId): object
{
        // Create runtime character
        $character = $this->gameRepository->createFromTemplate($userId, $templateId);
        //Starting room
        $startingRoomId = 5;
        // Generate dungeon
        $dungeonId = $this->generateDungeon($character->id, $startingRoomId);
        //get the starting room
        
        $room = $this->roomRepository->createRoomFromTemplate($dungeonId, $startingRoomId);
        //updates dungeon current room
        $this->updateCurrentRoom($dungeonId, $room['id']);
        return (object)[
            'character' => $character,
            'dungeonId'   => $dungeonId,
            'room' => $room
        ];
    }

     public function getCharacterById(int $characterId){
        return $this->gameRepository->getCharacterById($characterId);
     }

    /* ===== Movement ===== */
    
    public function getCurrentRoom(int $dungeonId) {
        return $this->gameRepository->getCurrentRoom($dungeonId);
    }

    public function updateCurrentRoom(int $dungeonId, int $roomId) {
        return $this->gameRepository->updateCurrentRoom($dungeonId, $roomId);
    }
    public function getCurrentRoomId(int $dungeonId) {
        return $this->gameRepository->getCurrentRoomId($dungeonId);
    }

    
    public function chooseDirection(int $dungeonId, string $direction) {
        if (!in_array($direction, ['north', 'south', 'east', 'west'])) {
            throw new \InvalidArgumentException("Invalid direction: $direction");
        }

        $currentRoom = $this->gameRepository->getCurrentRoom($dungeonId);
 
        if (!$currentRoom){
            return [
                'success' => false,
                'message' => 'Current room not found.'
            ];
        }

        $result = $this->gameRepository->chooseDirection($dungeonId, $direction);
        if (!isset($result['success']) || !$result['success']) {
            return [
                'success' => false,
                'message' => 'You walk into a wall. You must choose another direction.'
            ];
        }
        $nextRoomId = $result['next_room_id']; //get the id from the array

        $this->roomRepository->markDiscovered($nextRoomId);

        return [
            'success' => true,
            'roomId' => $nextRoomId
        ];
    }
    public function buildRoomLogResponse(int $roomId) {
        $room = $this->roomRepository->getRoomById($roomId);
        $data = [
            'roomName' => $room['name'],
            'description' => $room['description'],
            'type' => $room['type']
        ];

        if ($room['type'] === 'monster') {
            $monster = $this->gameRepository->getMonsterForRoom($roomId);
            if ($monster) {
                $data['monster'] = [
                    'name' => $monster->name,
                    'img' => $monster->img,
                    'currentHp' => $monster->currentHp,
                    'base_hp' => $monster->hp,
                    'base_strength' => $monster->strength,
                    'xp_reward' => $monster->xp_reward
                ];
            }
        }

        return $data;
    }

     /* ===== Monster ===== */
     public function getMonsterForRoom(int $roomId){
        return $this->gameRepository->getMonsterForRoom($roomId);
     }

     /* ===== Combat ===== */
     public function attackMonster(int $characterId, int $roomId)
     {
        $character = $this->gameRepository->getCharacterById($characterId);
        $monster = $this->gameRepository->getMonsterForRoom($roomId);

        $result = [
            'log' => [],
            'playerAttack' => null,
            'monsterAttack' => null,
            'monsterDefeated' => false,
            'playerDefeated' => false,
            'xpGained' => 0,
            'monsterHp' => null,
            'characterHp' => null
        ];

        if (!$character || !$monster) {
            $result['log'][] = ['type' => 'info', 'text' => "Error: Character or monster not found."];
            return $result;
        }

        //cant use object properties in json response
        $monsterName = $monster->name;
        $monsterDex = $monster->dex;
        $monsterStrength = $monster->strength;
        $monsterHp = $monster->currentHp;
        $monsterXpReward = $monster->xp_reward;

        $characterName = $character->name;
        $characterDex = $character->dex;
        $characterStrength = $character->strength;
        $characterHp = $character->currentHp;

        $monsterAc = 10 + floor($monsterDex / 2);

        // Player attacks monster
        $playerRoll = Dice::rollWithStats($characterStrength);
        $playerDamage = 0;
        $playerHit = $playerRoll >= $monsterAc;

        $result['log'][] = ['type' => 'attack', 'text' => "{$characterName} attack the {$monsterName} with a roll of {$playerRoll} against it's AC of {$monsterAc}."];
        if ($playerHit) {
            $playerDamage = Dice::damage(1, 8) + floor($characterStrength / 2);
            $this->gameRepository->damageMonster($roomId, $playerDamage);

            $result['log'][] = ['type' => 'hit', 'text' => "{$characterName} attack hits and deals {$playerDamage} damage!"];
        } else {
            $result['log'][] = ['type' => 'miss', 'text' => "{$characterName} attack misses!"];
        }

        $result['playerAttack'] =[
            'roll' => $playerRoll,
            'damage' => $playerDamage,
            'monsterAC' => $monsterAc,
            'playerHit' => $playerHit,      
        ];

        //Checks if monster is dead and add xp
        if(!$this->gameRepository->checkIfMonsterIsAlive($roomId)){

            $xpGained = $monsterXpReward;
            $result['monsterDefeated'] = true;
            $result['xpGained'] = (int)$xpGained;
            $result['monsterHp'] = 0;
            $result['characterHp'] = (int)$characterHp; 

            $this->gameRepository->addXP($characterId, $xpGained);
            $this->gameRepository->clearMonsterFromRoom($roomId);

            $result['log'][] = ['type' => 'win', 'text' => "You defeated the {$monsterName} and gained {$result['xpGained']} XP!"];
            $result['log'][] = ['type' => 'info', 'text' => "Now you may proceed to the next room."];
            return $result;
        }
         // Refresh monster data after damage
        $monster = $this->gameRepository->getMonsterForRoom($roomId); 
        $monsterHp = $monster->currentHp; // Update monster HP after damage
        $result['monsterHp'] = (int)$monsterHp;
        $result['log'][] = ['type' => 'info', 'text' => "The {$monsterName} has {$result['monsterHp']} HP remaining."];

        // Monster attacks player
        $monsterRoll = Dice::rollWithStats($monsterStrength);
        $monsterDamage = 0;
        $playerAc = 10 + floor($characterDex / 2);

        $result['log'][] = ['type' => 'attack', 'text' => "The {$monsterName} attacks {$characterName} with a roll of {$monsterRoll} against {$characterName} AC of {$playerAc}."];

        $monsterHit = $monsterRoll >= $playerAc;
        if ($monsterHit) {
            $monsterDamage = Dice::damage(1, 6) + floor($monsterStrength / 2);
            $this->gameRepository->damageCharacter($characterId, $monsterDamage);
            $characterHp = $character->currentHp; // Update character HP after damage
            $result['log'][] = ['type' => 'hit', 'text' => "The {$monsterName}'s attack hits and deals {$monsterDamage} damage!"];
        }else {
            $result['log'][] = ['type' => 'miss', 'text' => "The {$monsterName}'s attack misses!"];
        }

        $result['monsterAttack'] = [
            'roll' => $monsterRoll,
            'damage' => $monsterDamage,
            'playerAC' => $playerAc,
            'monsterHit' => $monsterHit,
            'damageDealt' => $monsterDamage
        ];

        //$character = $this->gameRepository->getCharacterById($characterId); // Refresh character data
        // Check if player is defeated
        if(!$this->gameRepository->checkIfCharacterIsAlive($characterId)){
            $result['playerDefeated'] = true;
            $result['characterHp'] = 0;
            $result['log'][] = ['type' => 'gameOver', 'text' => "You have been defeated by the {$monster->name}... All is lost, and you fade into the abyss. Game Over."];
            return $result;
        }

        $result['characterHp'] = (int)$characterHp;
        $result['log'][] = ['type' => 'info', 'text' => "You have {$result['characterHp']} HP remaining."];

        return $result;
    }

    /* ===== Dungeon ===== */
    public function getDungeonById(int $dungeonId) {
        return $this->gameRepository->getDungeonById($dungeonId);
    }

     public function generateDungeon(int $characterId, int $startingRoomId = 30) {
        return $this->gameRepository->generateDungeon($characterId, $startingRoomId);
    }

    public function randomizeRooms($rooms) {
        return $this->gameRepository->randomizeRooms($rooms);
    }
    
}