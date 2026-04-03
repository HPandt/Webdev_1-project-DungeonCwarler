<?php 

namespace App\Services;

use App\Repos\Interfaces\IRoomRepo;
use App\Services\Interfaces\IRoomService;

class RoomService implements IRoomService {
    private IRoomRepo $roomRepository;
    public function __construct(IRoomRepo $roomRepository) {
        $this->roomRepository = $roomRepository;
    }
    // Implementation of room service methods
    public function getRoomById(int $roomId) {
        return $this->roomRepository->getRoomById($roomId);
    }
    public function getNextRoom(array $currentRoom, string $dir) {
        return $this->roomRepository->getNextRoom($currentRoom, $dir);
    }
    public function markDiscovered(int $roomId) {
        return $this->roomRepository->markDiscovered($roomId);
    }
    public function updateRoomDirection(int $roomId, string $direction, int $nextRoomId) {
        return $this->roomRepository->updateRoomDirection($roomId, $direction, $nextRoomId);
    }
    
}