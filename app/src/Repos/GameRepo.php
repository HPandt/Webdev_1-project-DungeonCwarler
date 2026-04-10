<?php

namespace App\Repos;

use App\Models\CharacterModel;
use App\Models\MonsterModel;
use App\Models\ViewModels\MonsterViewModel;
use App\Repos\Interfaces\IGameRepo;
use App\Repos\RoomRepo;
use App\Core\Repository;
use App\Models\ViewModels\RoomsViewModel;
use App\Models\Enums\CharacterClass;
use PDOException;

class GameRepo extends Repository implements IGameRepo
{

    private RoomRepo $roomRepository;

    public function __construct()
    {
        $this->roomRepository = new RoomRepo();
    }

    public function generateDungeon($characterId, $startingRoomId)
    {
        try {
            // Creates a new dungeon for selected character and starts at first room ID -30
            $sql = "INSERT INTO Dungeon (character_id, current_room_id) VALUES (?, ?)";
            $createDungeon = $this->getConnection()->prepare($sql);
            $createDungeon->execute([$characterId, $startingRoomId]);
            // Return last inserted ID
            return $this->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException('Error creating dungeon: ' . $e->getMessage());
        }
    }

    public function createFromTemplate(int $userId, int $templateId): object
    {
        try {
            //Create instance character from template
            $sql =
                "
                INSERT INTO Characters (user_id, template_id, current_hp, bonus_strength, bonus_dex, bonus_luck, level, xp, is_active)
                SELECT
                    :userId,
                    t.id,
                    t.base_hp,
                    t.base_strength,
                    t.base_dex,
                    t.base_luck,
                    1,
                    0,
                    1
                FROM CharacterTemplate t
                WHERE t.id = :templateId
            ";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([
                'userId'     => $userId,
                'templateId' => $templateId
            ]);

            return $this->getCharacterById($this->getConnection()->lastInsertId());
        } catch (PDOException $e) {
            throw new PDOException('Error creating character from template: ' . $e->getMessage());
        }
    }


    public function getCharacterById(int $characterId)
    {
        try {
            //Have to get all character info from both characters and character template tables to create the character model
            $sql = "SELECT c.id, c.user_id, c.level, c.current_hp, c.xp, t.name,
            t.img, t.class, t.base_hp, t.base_strength, t.base_dex, t.base_luck
            FROM Characters c JOIN CharacterTemplate t ON t.id = c.template_id WHERE c.id = :id";
            $getCharacterById = $this->getConnection()->prepare($sql);
            $getCharacterById->execute([':id' => $characterId]);
            $row = $getCharacterById->fetch();

            if (!$row) {
                throw new \RuntimeException("Character not found");
            }

            return new CharacterModel(
                $row['id'],
                $row['user_id'],
                $row['name'],
                $row['img'] ?? null,
                CharacterClass::from($row['class']),
                (int)$row['level'],
                (int)$row['base_hp'],
                (int)$row['current_hp'],
                (int)$row['base_strength'],
                (int)$row['base_dex'],
                (int)$row['base_luck'],
                (int)$row['xp']
            );
        } catch (PDOException $e) {
            throw new PDOException('Error fetching character by id: ' . $e->getMessage());
        }
    }

    public function getMonsterForRoom(int $roomId)
    {
        try {
            //Get monster info from template into room and make instance and if monster hp is zero dont get it
            $sql = "SELECT m.id AS monster_template_id,
            m.name, m.img, r.monster_current_hp, m.base_hp, m.base_strength, m.base_dex, m.xp_reward 
            FROM Rooms r
            JOIN MonsterTemplate m ON m.id = r.monster_temp_id 
            WHERE r.id = :roomId
            AND r.monster_temp_id IS NOT NULL 
            AND r.monster_current_hp > 0
            LIMIT 1";
            $getMonster = $this->getConnection()->prepare($sql);
            $getMonster->execute(['roomId' => $roomId]);
            $row = $getMonster->fetch(\PDO::FETCH_ASSOC);

            if (!$row || empty($row['monster_template_id']) || empty($row['name'])) {
                //no monster in this room or the monster row is invalid
                return null;
            }

            $img = $row['img'] ?? '';

            return new MonsterModel(
                (int)$row['monster_template_id'],
                $row['name'],
                $img,
                (int)$row['base_hp'],
                (int)$row['monster_current_hp'],
                (int)$row['base_strength'],
                (int)$row['base_dex'],
                (int)$row['xp_reward']
            );
        } catch (PDOException $e) {
            throw new PDOException('Error fetching monster for room: ' . $e->getMessage());
        }
    }

    public function kill(int $id): void
    {
        try {
            $stmt = $this->getConnection()->prepare(
                "UPDATE Characters SET is_active = 0 WHERE id = ?"
            );
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new PDOException('Error killing character: ' . $e->getMessage());
        }
    }

    public function randomizeRooms($rooms)
    {
        // For the randomized rooms
        $randomIndex = rand(1, 9);

        return $rooms[$randomIndex];
    }

    public function chooseDirection(int $dungeonId, string $direction)
    {
        if (!in_array($direction, ['north', 'south', 'east', 'west'], true)) {
            return [
                'success' => false,
                'message' => 'Invalid direction.'
            ];
        }

        try {
            $sql = "SELECT r.* FROM Dungeon d JOIN Rooms r ON r.id = d.current_room_id WHERE d.id = ?";
            $getRooms = $this->getConnection()->prepare($sql);
            $getRooms->execute([$dungeonId]);
            $currentRoom = $getRooms->fetchAll(\PDO::FETCH_ASSOC);

            if (!$currentRoom || !isset($currentRoom[0])) {
                return [
                    'success' => false,
                    'message' => 'Current room not found.'
                ];
            }
            $currentRoom = $currentRoom[0]; // Get the first (and should be only) room

            //Direction logic 
            $dir = $direction . "_room_id";
            if (!empty($currentRoom[$dir])) {
                return ['success' => true, 'next_room_id' => $currentRoom[$dir]];
            }

            //randomize room from count in template table
            $countSql = "SELECT COUNT(*) as total FROM RoomTemplate";
            $countStmt = $this->getConnection()->prepare($countSql);
            $countStmt->execute();
            $totalRooms = (int)$countStmt->fetchColumn();
            $randomTemplateId = rand(5, $totalRooms);
            $newRoom = $this->roomRepository->createRoomFromTemplate($dungeonId, $randomTemplateId);

            if (!$newRoom) {
                return [
                    'success' => false,
                    'message' => 'Failed to create new room.'
                ];
            }
            $newRoomId = $newRoom['id'];
            $this->roomRepository->updateRoomDirection($currentRoom['id'], $direction, $newRoomId);

            $this->updateCurrentRoom($dungeonId, $newRoomId);
            return [
                'success' => true,
                'next_room_id' => $newRoomId
            ];
        } catch (PDOException $e) {
            throw new PDOException('Error choosing direction: ' . $e->getMessage());
        }
    }

    public function showRoom(int $dungeonId, int $roomId)
    {
        try {
            $sql = "SELECT * FROM Rooms WHERE id = ? AND dungeon_id = ?";
            $getRoom = $this->getConnection()->prepare($sql);
            $getRoom->execute([$roomId, $dungeonId]);
            return $getRoom->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException('Error fetching room: ' . $e->getMessage());
        }
    }

    public function addXP(int $characterId, int $roomId)
    {
        try {
            // When player defeats monster use experience points from right monster here xpamount
            $sql = "UPDATE Characters c JOIN Rooms r ON r.id = :roomId JOIN MonsterTemplate m ON m.id = r.monster_temp_id SET c.xp = c.xp + m.xp_reward WHERE c.id = :characterId";
            $addXp = $this->getConnection()->prepare($sql);
            $addXp->execute([
                ':roomId' => $roomId,
                ':characterId' => $characterId
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error adding XP: ' . $e->getMessage());
        }
    }

    public function damageCharacter(int $characterId, int $damage)
    {
        try {
            $sql = "UPDATE Characters SET current_hp = GREATEST(current_hp - :damage, 0) WHERE id = :characterId";
            $reduceHp = $this->getConnection()->prepare($sql);
            $reduceHp->execute([
                ':damage' => $damage,
                ':characterId' => $characterId
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error damaging character: ' . $e->getMessage());
        }
    }

    public function checkIfCharacterIsAlive(int $characterId)
    {
        try {
            $sql = "SELECT current_hp FROM Characters WHERE id = :characterId";
            $checkHp = $this->getConnection()->prepare($sql);
            $checkHp->execute([':characterId' => $characterId]);
            return ((int)$checkHp->fetchColumn()) > 0;
        } catch (PDOException $e) {
            throw new PDOException('Error checking character alive state: ' . $e->getMessage());
        }
    }

    public function clearMonsterFromRoom(int $roomId)
    {
        try {
            $sql = "UPDATE Rooms SET monster_temp_id = NULL, monster_current_hp = NULL WHERE id = :roomId";
            $clearMonster = $this->getConnection()->prepare($sql);
            $clearMonster->execute([':roomId' => $roomId]);
        } catch (PDOException $e) {
            throw new PDOException('Error clearing monster from room: ' . $e->getMessage());
        }
    }

    public function damageMonster(int $roomId, int $damage)
    {
        try {
            $sql = "UPDATE Rooms SET monster_current_hp = GREATEST(monster_current_hp - :damage, 0) WHERE id = :roomId";
            $reduceHp = $this->getConnection()->prepare($sql);
            $reduceHp->execute([
                ':damage' => $damage,
                ':roomId' => $roomId
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error damaging monster: ' . $e->getMessage());
        }
    }

    public function checkIfMonsterIsAlive(int $roomId)
    {
        try {
            $sql = "SELECT monster_current_hp FROM Rooms WHERE id = :roomId";
            $checkHp = $this->getConnection()->prepare($sql);
            $checkHp->execute([':roomId' => $roomId]);
            //this converts it into an int and checks if the life is 0 or less returning then true or false
            return ((int)$checkHp->fetchColumn()) > 0;
        } catch (PDOException $e) {
            throw new PDOException('Error checking monster alive state: ' . $e->getMessage());
        }
    }
    public function getCurrentRoom(int $dungeonId)
    {
        try {
            $sql = "SELECT r.*, rt.type, rt.name, rt.description 
            FROM Dungeon d 
            JOIN Rooms r ON r.id =  d.current_room_id
            JOIN RoomTemplate rt ON r.room_temp_id = rt.id 
            WHERE d.id = :dungeonId";
            $getRoom = $this->getConnection()->prepare($sql);
            $getRoom->execute(['dungeonId' => $dungeonId]);
            return $getRoom->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException('Error fetching current room: ' . $e->getMessage());
        }
    }

    public function getDungeonById($dungeonId)
    {
        try {
            $sql = "SELECT * FROM Dungeon WHERE id = ?";
            $getDungeon = $this->getConnection()->prepare($sql);
            $getDungeon->execute([$dungeonId]);
            return $getDungeon->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new PDOException('Error fetching dungeon: ' . $e->getMessage());
        }
    }

    public function updateCurrentRoom(int $dungeonId, int $roomId)
    {
        try {
            $sql = "UPDATE Dungeon SET current_room_id = :roomId WHERE id = :dungeonId";
            $updateRoom = $this->getConnection()->prepare($sql);
            $updateRoom->execute([
                ':roomId' => $roomId,
                ':dungeonId' => $dungeonId
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error updating current room: ' . $e->getMessage());
        }
    }
    public function getCurrentRoomId(int $dungeonId)
    {
        try {
            $sql = "SELECT current_room_id FROM Dungeon WHERE id = :dungeonId";
            $getRoomId = $this->getConnection()->prepare($sql);
            $getRoomId->execute([':dungeonId' => $dungeonId]);
            $result = $getRoomId->fetch(\PDO::FETCH_ASSOC);
            return $result ? (int)$result['current_room_id'] : null;
        } catch (PDOException $e) {
            throw new PDOException('Error fetching current room id: ' . $e->getMessage());
        }
    }
}
