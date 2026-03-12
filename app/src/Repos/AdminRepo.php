<?php 

namespace App\Repos;
use App\Repos\Interfaces\IAdminRepo;
use App\Core\Repository;
use App\Models\CharacterModel;
use App\Models\Templates\CharacterTemplate;
use App\Models\Templates\RoomsTemplate;
use App\Models\Enums\CharacterClass;
use App\Models\Enums\RoomType;
use App\Models\MonsterModel;
use App\Models\RoomsModel;
use App\Models\Templates\MonsterTemplate;

class AdminRepo extends Repository implements IAdminRepo{

    // Character management
    public function createCharacterTemplate(CharacterTemplate $characterTemplate)
    {
        $sql = "INSERT INTO CharacterTemplate 
        (name, img, class, base_hp, base_strength, base_dex, base_luck) 
        VALUES (:name, :img, :class, :base_hp, :base_strength, :base_dex, :base_luck)";
        $createCharacter = $this->getConnection()->prepare($sql);
        $createCharacter->execute([
            'name' => $characterTemplate->name,
            'img' => $characterTemplate->img,
            'class' => $characterTemplate->class,
            'base_hp' => $characterTemplate->maxHp,
            'base_strength' => $characterTemplate->strength,
            'base_dex' => $characterTemplate->dex,
            'base_luck' => $characterTemplate->luck
        ]);
        return $this->getConnection()->lastInsertId();
    }

    public function updateCharacterTemplate(CharacterTemplate $characterTemplate){
        $sql = "UPDATE CharacterTemplate SET 
        name = :name, img = :img, class = :class, base_hp = :base_hp, 
        base_strength = :base_strength, base_dex = :base_dex, base_luck = :base_luck
        WHERE id = :id";
        $updateCharacter = $this->getConnection()->prepare($sql);
        return $updateCharacter->execute([
            'id' => $characterTemplate->id,
            'name' => $characterTemplate->name,
            'img' => $characterTemplate->img,
            'class' => $characterTemplate->class,
            'base_hp' => $characterTemplate->maxHp,
            'base_strength' => $characterTemplate->strength,
            'base_dex' => $characterTemplate->dex,
            'base_luck' => $characterTemplate->luck
        ]);
    }

    public function getAllCharacterTemplates()
    {
        $sql = "SELECT * FROM CharacterTemplate";
        $getAllCharacters = $this->getConnection()->prepare($sql);
        $getAllCharacters->execute();
        $rows =  $getAllCharacters->fetchAll();
        $templates = [];

        foreach ($rows as $row) {
            $templates[] = new CharacterTemplate(
                (int)$row['id'],
                $row['name'],
                $row['img'] ?? null,
                CharacterClass::from($row['class']),
                (int)$row['base_hp'],
                (int)$row['base_strength'],
                (int)$row['base_dex'],
                (int)$row['base_luck']
            );
        }
        return $templates;
    }

    public function getCharacterTemplateById(int $characterId)
    {
        $sql = "SELECT * FROM CharacterTemplate WHERE id = :id";
        $getCharacter = $this->getConnection()->prepare($sql);
        $getCharacter->execute([':id' => $characterId]);
        return $getCharacter->fetch();
    }

    public function deleteCharacterTemplate(int $characterId)
    {
        $sql = "DELETE FROM CharacterTemplate WHERE id :id";
        $deleteCharacter = $this->getConnection()->prepare($sql);
        $deleteCharacter->execute([':id' => $characterId]);
    }

    //Monster management
    public function createMonsterTemplate(MonsterTemplate $monsterTemplate)
    {
        $sql = "INSERT INTO MonsterTemplate
        (name, img, base_hp, base_strength, base_dex, xp_reward) VALUES 
        (:name, :img, :hp, :strength, :dex, :xp_reward)";
        $createMonster = $this->getConnection()->prepare($sql);
        return $createMonster->execute([
            'name' => $monsterTemplate->name,
            'img' => $monsterTemplate->img,
            'base_hp' => $monsterTemplate->hp,
            'base_strength' => $monsterTemplate->strength,
            'base_dex' => $monsterTemplate->dex,
            'xp_reward' => $monsterTemplate->xp_reward
        ]);
    }

    public function updateMonsterTemplate(MonsterTemplate $monsterTemplate)
    {
        $sql = "UPDATE MonsterTemplate SET 
        name = :name, img = :img, base_hp = :hp, base_strength = :strength, 
        base_dex = :dex, xp_reward = :xp_reward WHERE id = :id";
        $updateMonster = $this->getConnection()->prepare($sql);
        return $updateMonster->execute([
            'id' => $monsterTemplate->id,
            'name' => $monsterTemplate->name,
            'img' => $monsterTemplate->img,
            'base_hp' => $monsterTemplate->hp,
            'base_strength' => $monsterTemplate->strength,
            'base_dex' => $monsterTemplate->dex,
            'xp_reward' => $monsterTemplate->xp_reward
        ]);
    }

    public function getAllMonsterTemplates()
    {
        $sql = "SELECT * FROM MonsterTemplate";
        $getAllMonsters = $this->getConnection()->prepare($sql);
        $getAllMonsters->execute();
        $rows = $getAllMonsters->fetchAll();

        $monsterTemplate =[];
        foreach($rows as $row){
            $monsterTemplate[] = new MonsterTemplate(
                (int)$row['id'],
                $row['name'],
                $row['img'],
                (int)$row['base_hp'],
                (int)$row['base_strength'],
                (int)$row['base_dex'],
                (int)$row['xp_reward']
            );
        }

        return $monsterTemplate;
    }

    public function getMonsterTemplateById(int $monsterId)
    {
        $sql = "SELECT * FROM MonsterTemplate WHERE id = :id";
        $getMonster = $this->getConnection()->prepare($sql);
        $getMonster->execute([':id' => $monsterId]);
        return $getMonster->fetch();
    }

    public function deleteMonsterTemplate(int $monsterId)
    {
        $sql = "DELETE FROM MonsterTemplate WHERE id = :id";
        $deleteMonster = $this->getConnection()->prepare($sql);
        $deleteMonster->execute([':id' => $monsterId]);
    }

    // Room management
    public function createRoomTemplate(RoomsTemplate $roomTemplate)
    {
        $sql = "INSERT INTO RoomsTemplate (name, description, type, monster_template_id, trap_damage) VALUES (:name, :description, :type, :monster_template_id, :trap_damage)";
        $createRoom = $this->getConnection()->prepare($sql);
        return $createRoom->execute([
            'name' => $roomTemplate->name,
            'description' => $roomTemplate->description,
            'type' => $roomTemplate->type,
            'monster_template_id' => $roomTemplate->monsterId,
            'trap_damage' => $roomTemplate->trapDamage
        ]);
    }

    public function deleteRoomTemplate(int $roomId)
    {
        $sql = "DELETE FROM RoomsTemplate WHERE id = :id";
        $deleteRoom = $this->getConnection()->prepare($sql);
        $deleteRoom->execute([':id' => $roomId]);
    }

    public function updateRoomTemplate(RoomsTemplate $roomTemplate)
    {
        $sql = "UPDATE RoomsTemplate SET name = :name, description = :description, type = :type, monster_template_id = :monster_template_id, trap_damage = :trap_damage WHERE id = :id";
        $updateRoom = $this->getConnection()->prepare($sql);
        return $updateRoom->execute([
            'id' => $roomTemplate->id,
            'name' => $roomTemplate->name,
            'description' => $roomTemplate->description,
            'type' => $roomTemplate->type,
            'monster_template_id' => $roomTemplate->monsterId,
            'trap_damage' => $roomTemplate->trapDamage
        ]);
    }

    public function getAllRoomTemplates()
    {
        $sql = "SELECT * FROM Rooms";
        $getAllRooms = $this->getConnection()->prepare($sql);
        $getAllRooms->execute();
        $rows = $getAllRooms->fetchAll();

        $roomTemplate = [];
        foreach($rows as $row){
            $roomTemplate[] = new RoomsTemplate(
                (int)$row['id'],
                $row['name'],
                $row['description'],
                RoomType::from($row['type']),
                (int)$row['monster_trmplate'],
                (int)$row['trap_damage']
            );
        }
        return $roomTemplate;
    }

    public function getRoomTemplateById(int $roomId)
    {
        $sql = "SELECT * FROM Rooms WHERE id = :id";
        $getRoomById = $this->getConnection()->prepare($sql);
        $getRoomById->execute([':id' => $roomId]);
        return $getRoomById->fetch();
    }


    public function deleteUser(int $userId)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $deleteUser = $this->getConnection()->prepare($sql);
        $deleteUser->execute([':id' => $userId]);
    }

    public function getUserById(int $userId)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $getUserById = $this->getConnection()->prepare($sql);
        $getUserById->execute([':id' => $userId]);
        return $getUserById->fetch();
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $getAllUsers = $this->getConnection()->prepare($sql);
        $getAllUsers->execute();
        return $getAllUsers->fetchAll();
    }

}