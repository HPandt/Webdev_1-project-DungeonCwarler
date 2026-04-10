<?php 

namespace App\Repos;
use App\Repos\Interfaces\IRoomRepo;
use App\Core\Repository;
use PDOException;

class RoomRepo extends Repository implements IRoomRepo{

    public function getRoomById(int $roomId) {
        try {
            $sql = "SELECT r.*, rt.name, rt.description, rt.type 
            FROM Rooms r 
            JOIN RoomTemplate rt ON r.room_temp_id = rt.id 
            WHERE r.id = :roomId";
            $getRoom = $this->getConnection()->prepare($sql);
            $getRoom->execute(['roomId' => $roomId]);
            return $getRoom->fetch();
        } catch (PDOException $e) {
            throw new PDOException('Error fetching room by id: ' . $e->getMessage());
        }
    }

    public function getNextRoom(array $currentRoom, string $dir) {
        try {
            $nextRoomId = $currentRoom[$dir . '_room_id'];
            return $this->getRoomById($nextRoomId);
        } catch (PDOException $e) {
            throw new PDOException('Error fetching next room: ' . $e->getMessage());
        }
    }

    public function markDiscovered(int $roomId) {
        try {
            $sql = "UPDATE Rooms SET discovered = 1 WHERE id = ?";
            $setMarked = $this->getConnection()->prepare($sql);
            return $setMarked->execute([$roomId]);
        } catch (PDOException $e) {
            throw new PDOException('Error marking room discovered: ' . $e->getMessage());
        }
    }

    public function createRoomFromTemplate(int $dungeonId, int $templateId)
    {
        try {
            //get template to check if it has monster and get monster hp
            $templateSql = "SELECT * FROM RoomTemplate WHERE id = ?";
            $getTemplate = $this->getConnection()->prepare($templateSql);
            $getTemplate->execute([$templateId]);
            $template = $getTemplate->fetch(\PDO::FETCH_ASSOC);
            if(!$template){
                return null;
            }

            //get data if room has monster in the template
            $monsterTempId = $template['monster_template'];
            $monsterCurrentHp = null;
            if ($monsterTempId) {
                //get monster base hp from said template
                $monsterSql = "SELECT base_hp FROM MonsterTemplate WHERE id = ?";
                $getMonster = $this->getConnection()->prepare($monsterSql);
                $getMonster->execute([$monsterTempId]);
                $monster = $getMonster->fetch(\PDO::FETCH_ASSOC);
                if ($monster) {
                    //set current hp to base, this is what the game will use to track its hp 
                    $monsterCurrentHp = $monster['base_hp']; 
                }
            }

            $sql = "
                INSERT INTO Rooms (dungeon_id, room_temp_id, discovered, monster_temp_id, monster_current_hp)
                VALUES (:dungeonId, :templateId, 1, :monsterTempId, :monsterCurrentHp)
            ";  
            $stmt = $this->connect()->prepare($sql);
            if (!$stmt->execute([
                'dungeonId' => $dungeonId,
                'templateId' => $templateId,
                'monsterTempId' => $monsterTempId,
                'monsterCurrentHp' => $monsterCurrentHp
            ])) {
                $error = $stmt->errorInfo();
                throw new \Exception('Error creating room from template: ' . implode(' | ', $error));
            }

            $roomId = $this->getConnection()->lastInsertId();
            return $this->getRoomById($roomId);
        } catch (PDOException $e) {
            throw new PDOException('Error creating room from template: ' . $e->getMessage());
        }
    }

    public function updateRoomDirection(int $roomId, string $direction, int $nextRoomId) {
        try {
            $directionColumn = $direction . '_room_id';    
            $sql = "UPDATE Rooms SET {$directionColumn} = :nextRoomId WHERE id = :roomId";
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute([
                'nextRoomId' => $nextRoomId,
                'roomId' => $roomId
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error updating room direction: ' . $e->getMessage());
        }
    }
}