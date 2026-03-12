<?php 

namespace App\Repos;
use App\Repos\Interfaces\IRoomRepo;
use App\Core\Repository;

class RoomRepo extends Repository implements IRoomRepo{

    public function getRoomById(int $roomId) {
        $sql = "SELECT r.*, rt.name, rt.description, rt.type 
        FROM Rooms r 
        JOIN RoomTemplate rt ON r.room_temp_id = rt.id 
        WHERE r.id = :roomId";
        $getRoom = $this->getConnection()->prepare($sql);
        $getRoom->execute(['roomId' => $roomId]);
        return $getRoom->fetch();
    }

    public function getNextRoom(array $currentRoom, string $dir) {
        $nextRoomId = $currentRoom[$dir . '_room_id'];
        return $this->getRoomById($nextRoomId);
    }

    public function markDiscovered(int $roomId) {
        $sql = "UPDATE Rooms SET is_discovered = 1 WHERE id = ?";
        $setMarked = $this->getConnection()->prepare($sql);
        return $setMarked->execute([$roomId]);
    }

    public function createRoomFromTemplate(int $dungeonId, int $templateId)
    {
        $sql = "
            INSERT INTO Rooms (dungeon_id, room_temp_id, discovered)
            VALUES (:dungeonId, :templateId, 1)
        ";  
        $stmt = $this->connect()->prepare($sql);
        if (!$stmt->execute([
            'dungeonId' => $dungeonId,
            'templateId' => $templateId
        ])) {
            var_dump($stmt->errorInfo());
            die();
        }
        // $stmt->execute([
        //     'dungeonId' => $dungeonId,
        //     'templateId' => $templateId
        // ]);
        $roomId = $this->getConnection()->lastInsertId();
        return $this->getRoomById($roomId);
    }
}