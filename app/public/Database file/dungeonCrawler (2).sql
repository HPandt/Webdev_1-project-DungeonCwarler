-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 06, 2026 at 08:35 AM
-- Server version: 12.0.2-MariaDB-ubu2404
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dungeonCrawler`
--

-- --------------------------------------------------------

--
-- Table structure for table `Characters`
--

CREATE TABLE `Characters` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `current_hp` int(11) NOT NULL,
  `bonus_strength` int(11) NOT NULL DEFAULT 5,
  `bonus_dex` int(11) NOT NULL DEFAULT 5,
  `bonus_luck` int(11) NOT NULL DEFAULT 5,
  `level` int(11) NOT NULL DEFAULT 1,
  `xp` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `Characters`
--

INSERT INTO `Characters` (`id`, `user_id`, `template_id`, `current_hp`, `bonus_strength`, `bonus_dex`, `bonus_luck`, `level`, `xp`, `is_active`, `created_at`) VALUES
(1, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:12:13'),
(2, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:13:49'),
(3, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:18:13'),
(4, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:20:28'),
(5, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:29:46'),
(6, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:33:41'),
(7, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:37:35'),
(8, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-03-12 15:56:37'),
(9, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 15:56:49'),
(10, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 16:01:28'),
(11, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 16:01:59'),
(12, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 16:02:30'),
(13, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 16:02:42'),
(14, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 16:02:55'),
(15, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-12 16:06:10'),
(16, 3, 4, 150, 8, 6, 4, 1, 0, 1, '2026-03-16 11:39:52'),
(17, 3, 4, 150, 8, 6, 4, 1, 0, 1, '2026-03-25 22:12:07'),
(18, 3, 2, 80, 5, 7, 7, 1, 0, 1, '2026-03-26 12:25:43'),
(19, 3, 2, 64, 5, 7, 7, 1, 0, 1, '2026-04-01 09:15:44'),
(20, 4, 1, 97, 7, 5, 3, 1, 0, 1, '2026-04-01 19:15:09'),
(21, 3, 2, 75, 5, 7, 7, 1, 0, 1, '2026-04-02 07:54:30'),
(22, 3, 1, 120, 7, 5, 3, 1, 0, 1, '2026-04-02 09:14:30'),
(23, 3, 4, 6, 8, 6, 4, 1, 0, 1, '2026-04-02 11:06:13'),
(24, 3, 1, 71, 7, 5, 3, 1, 0, 1, '2026-04-02 17:24:58'),
(25, 3, 3, 0, 5, 6, 5, 1, 0, 1, '2026-04-03 14:27:24'),
(26, 3, 3, 70, 5, 6, 5, 1, 0, 1, '2026-04-03 18:17:27'),
(27, 4, 4, 20, 8, 6, 4, 1, 0, 1, '2026-04-03 19:31:05'),
(28, 4, 6, 89, 7, 6, 4, 1, 0, 1, '2026-04-03 21:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `CharacterTemplate`
--

CREATE TABLE `CharacterTemplate` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `class` enum('warrior','rouge','mage','barbarian') NOT NULL,
  `base_hp` int(11) NOT NULL,
  `base_strength` int(11) NOT NULL,
  `base_dex` int(11) NOT NULL,
  `base_luck` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `CharacterTemplate`
--

INSERT INTO `CharacterTemplate` (`id`, `name`, `img`, `class`, `base_hp`, `base_strength`, `base_dex`, `base_luck`) VALUES
(2, 'Derek Silverfinger', 'rouge.png', 'rouge', 80, 5, 7, 7),
(3, 'Lucy Blizzard', 'Mage.png', 'mage', 70, 5, 6, 5),
(4, 'Ulrich Bloodaxe', 'Barbarian.png', 'barbarian', 150, 8, 6, 4),
(6, 'Alice Hermaiden', 'warrior.png', 'warrior', 120, 7, 6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `Dungeon`
--

CREATE TABLE `Dungeon` (
  `id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `current_room_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `Dungeon`
--

INSERT INTO `Dungeon` (`id`, `character_id`, `current_room_id`, `is_active`, `created_at`) VALUES
(23, 26, 108, 1, '2026-04-03 18:17:27'),
(24, 27, 125, 1, '2026-04-03 19:31:06'),
(25, 28, 129, 1, '2026-04-03 21:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `Logs`
--

CREATE TABLE `Logs` (
  `id` int(11) NOT NULL,
  `character_id` int(11) DEFAULT NULL,
  `monster_id` int(11) DEFAULT NULL,
  `action` text NOT NULL,
  `result` text NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `MonsterTemplate`
--

CREATE TABLE `MonsterTemplate` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `img` varchar(50) DEFAULT NULL,
  `base_hp` int(11) NOT NULL,
  `base_strength` int(11) NOT NULL,
  `base_dex` int(11) NOT NULL,
  `xp_reward` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `MonsterTemplate`
--

INSERT INTO `MonsterTemplate` (`id`, `name`, `img`, `base_hp`, `base_strength`, `base_dex`, `xp_reward`) VALUES
(1, 'Cave Goblin', NULL, 20, 4, 5, 15),
(2, 'Kobold', 'Kobold.png', 25, 6, 5, 20),
(3, 'Orc', 'Orc.png', 35, 10, 5, 30),
(4, 'Skeleton Knight', 'Skeleton_Knight.png', 40, 8, 6, 30),
(5, 'Hunter Pandt', NULL, 10, 1, 1, 100);

-- --------------------------------------------------------

--
-- Table structure for table `Rooms`
--

CREATE TABLE `Rooms` (
  `id` int(11) NOT NULL,
  `dungeon_id` int(11) NOT NULL,
  `room_temp_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `north_room_id` int(11) DEFAULT NULL,
  `south_room_id` int(11) DEFAULT NULL,
  `east_room_id` int(11) DEFAULT NULL,
  `west_room_id` int(11) DEFAULT NULL,
  `discovered` tinyint(1) NOT NULL DEFAULT 0,
  `monster_temp_id` int(11) DEFAULT NULL,
  `monster_current_hp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `Rooms`
--

INSERT INTO `Rooms` (`id`, `dungeon_id`, `room_temp_id`, `description`, `north_room_id`, `south_room_id`, `east_room_id`, `west_room_id`, `discovered`, `monster_temp_id`, `monster_current_hp`) VALUES
(108, 23, 5, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(109, 24, 5, NULL, 110, NULL, NULL, NULL, 1, NULL, NULL),
(110, 24, 16, NULL, NULL, NULL, NULL, 111, 1, NULL, NULL),
(111, 24, 20, NULL, 112, NULL, NULL, NULL, 1, NULL, NULL),
(112, 24, 18, NULL, 113, NULL, NULL, NULL, 1, 1, 20),
(113, 24, 9, NULL, 114, NULL, NULL, NULL, 1, NULL, NULL),
(114, 24, 12, NULL, 115, NULL, NULL, NULL, 1, 3, 0),
(115, 24, 15, NULL, 116, NULL, NULL, NULL, 1, NULL, NULL),
(116, 24, 8, NULL, 117, NULL, NULL, NULL, 1, NULL, NULL),
(117, 24, 5, NULL, 118, NULL, NULL, NULL, 1, NULL, NULL),
(118, 24, 18, NULL, NULL, NULL, NULL, 119, 1, 1, 20),
(119, 24, 7, NULL, 120, NULL, NULL, NULL, 1, NULL, NULL),
(120, 24, 17, NULL, 121, NULL, NULL, NULL, 1, 2, 0),
(121, 24, 9, NULL, 122, NULL, NULL, NULL, 1, NULL, NULL),
(122, 24, 22, NULL, 123, NULL, NULL, NULL, 1, 3, 0),
(123, 24, 9, NULL, 124, NULL, NULL, NULL, 1, NULL, NULL),
(124, 24, 8, NULL, 125, NULL, NULL, NULL, 1, NULL, NULL),
(125, 24, 14, NULL, NULL, NULL, NULL, NULL, 1, 4, 0),
(126, 25, 5, NULL, 127, NULL, NULL, NULL, 1, NULL, NULL),
(127, 25, 11, NULL, 128, NULL, NULL, NULL, 1, 1, 20),
(128, 25, 12, NULL, 129, NULL, NULL, NULL, 1, NULL, NULL),
(129, 25, 26, NULL, NULL, NULL, NULL, NULL, 1, 4, 40);

-- --------------------------------------------------------

--
-- Table structure for table `RoomTemplate`
--

CREATE TABLE `RoomTemplate` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `type` enum('entrance','empty','monster','trap','exit') NOT NULL,
  `monster_template` int(11) DEFAULT NULL,
  `trap_damage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `RoomTemplate`
--

INSERT INTO `RoomTemplate` (`id`, `name`, `description`, `type`, `monster_template`, `trap_damage`) VALUES
(5, 'Entrance', 'You stand in a dimly lit stone chamber. Moist air drips from the ceiling, and you have no recollection of how you got here. All you know is you must escape, proceed young traveler. This is where your story begins', 'entrance', NULL, NULL),
(6, 'Hallway', 'You continue forward into the darkness.', 'empty', NULL, NULL),
(7, 'Hallway 2', 'You press on into the unknown. only being guided by the faint light of the torches and the sound of water droplets', 'empty', NULL, NULL),
(8, 'Empty Room', 'You stumble into an empty room, made of marble, you see multiple door in each direction. Do you brave it or turn back.', 'empty', NULL, NULL),
(9, 'A rusty room', 'You enter a new room and rusty metal plates cover the floor. Something beneath them clicks as you step forward.\'', 'trap', NULL, 8),
(10, 'A walking trap', 'You walked upon a tile floor, but as you took a step, you heard a click and an arrow shot from the wall of the room at you.', 'trap', NULL, 6),
(11, 'Goblin ambush', 'Upon opening the door, a guttural growl echoes through the darkness. A creature watches you from the shadows.', 'monster', 1, NULL),
(12, 'An Orc!', 'Opening the door, as the light shines in from the candle, you notice a monster standing in your way. A massive and borish figure it grawls and charges at you.', 'monster', 3, NULL),
(13, 'An dazzling light', 'As you walk, you notice a bright light in the distance, and a door stands before you. You open it and step out into the sunlight. You are now free!', 'exit', NULL, NULL),
(14, 'An old wandering knight', 'You entered the room and realized quickly that the atmosphere was wrong. In the center, a knight long past his days and time stood blade in hand. Skin of ash, blood of blue fire, it was a death knight. Ready yourself, you may not survive!', 'monster', 4, NULL),
(15, 'A quiet and empty room', 'A room of respite, no monster or traps in sight. You can catch your bearings and venture forth with no struggle', 'empty', NULL, NULL),
(16, 'Greedy boy', 'While travering the dungeon you notice something shiny on the floor, plague with greed you reach and it was a trap.You curse your bad luck', 'trap', NULL, 7),
(17, 'Not a Goblin!', 'While walking through the dungeon, you hear a strange noise. in the darkness a monster leaps out at you!', 'monster', 2, NULL),
(18, 'Ambush but from what?', 'Entering the room youre welcome to darkness, something took out the lights, and now you must face it.', 'monster', 1, NULL),
(19, 'I\'m safe', 'Another empty room void of monsters and traps, you thank your gods for this blessing', 'empty', NULL, NULL),
(20, 'Another exit ', 'A massive stone archway looms ahead. Freedom lies beyond this final door. You open it to see yourself in a bustling area in some unknown town. You are free but still lost.', 'exit', NULL, NULL),
(21, 'Greed hurts', 'You stumble upon a treasure chest, and you pry it open to be hit with poison gas. old and not as lethal, but still, you feel the pain.', 'trap', NULL, 12),
(22, 'A monster', 'An Orc stands in the center of the room, starving, and the only food it can see is you, the poor traveler who just stumbled in', 'monster', 3, NULL),
(23, 'A darkened room', 'A room adorned in black slate and golden lining stands before you. You marvel at its beauty and wonder why such a room exists in this strange dungeon. But you have no time to ponder, you must move on', 'empty', NULL, NULL),
(24, 'Gobdarn Goblins are everywhere', 'As you walk into the room, you assume it is empty until a goblin attacks you from behind the door ', 'monster', 1, NULL),
(25, 'An arrow to the knee', 'Walking towards the next door, you stepped on a fake tile, and an arrow shot out of the wall at you', 'trap', NULL, 8),
(26, 'Knight long passed', 'A knight lay resting on a pillar, his structure long gone; only a skeleton remains. You tried to walk past but as you do, the flames of despair awaken the fallen on,e and its anger is directed at you', 'monster', 4, NULL),
(27, 'Golden room', 'A room made of gold of all kinds, it is a wonder who had the time and resources to use all this gold. A mural of a king with a purple halo is engraved on the floor. Could this mean something? Sadly, you have no time to ponder', 'empty', NULL, NULL),
(28, 'Not a Goblin!', 'While walking through the dungeon, you hear a strange noise. in the darkness a monster leaps out at you!', 'monster', 2, NULL),
(29, 'The massive beast', 'Opening the door, as the light shines in from the candle, you notice a monster standing in your way.', 'monster', 3, NULL),
(30, 'Hallway 3', 'You continue forward into the darkness.', 'empty', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` enum('player','admin') DEFAULT 'player',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@admin.nl', '$2y$12$01NZ0hNWp/hP3SqQg9Rw1e1djOMY0eF5Q4FNs9WPgQx31VXt5BVrW', 'admin', '2026-03-12 10:21:23'),
(3, 'player', 'player@fake.nl', '$2y$12$q6UMztr5G9IxwvGwP.qXUehSruUBjWpxcrqAP6HHsGncJeCw2eVJS', 'player', '2026-03-12 10:21:57'),
(4, 'McLaren_hp', 'maclaren@player.nl', '$2y$12$bORqGygeFroQod4VrtRYnOt6I583VE0Jq8LDy.duHrt4SBj4sIAbm', 'player', '2026-04-01 19:14:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Characters`
--
ALTER TABLE `Characters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `template_id` (`template_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `CharacterTemplate`
--
ALTER TABLE `CharacterTemplate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Dungeon`
--
ALTER TABLE `Dungeon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `character_id` (`character_id`);

--
-- Indexes for table `Logs`
--
ALTER TABLE `Logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `character_id` (`character_id`),
  ADD KEY `monster_id` (`monster_id`);

--
-- Indexes for table `MonsterTemplate`
--
ALTER TABLE `MonsterTemplate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Rooms`
--
ALTER TABLE `Rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_temp_id` (`room_temp_id`),
  ADD KEY `dungeon_id` (`dungeon_id`),
  ADD KEY `monster_temp_id` (`monster_temp_id`);

--
-- Indexes for table `RoomTemplate`
--
ALTER TABLE `RoomTemplate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `monster_template` (`monster_template`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Characters`
--
ALTER TABLE `Characters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `CharacterTemplate`
--
ALTER TABLE `CharacterTemplate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Dungeon`
--
ALTER TABLE `Dungeon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `MonsterTemplate`
--
ALTER TABLE `MonsterTemplate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Rooms`
--
ALTER TABLE `Rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `RoomTemplate`
--
ALTER TABLE `RoomTemplate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Characters`
--
ALTER TABLE `Characters`
  ADD CONSTRAINT `Characters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Characters_ibfk_2` FOREIGN KEY (`template_id`) REFERENCES `CharacterTemplate` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Dungeon`
--
ALTER TABLE `Dungeon`
  ADD CONSTRAINT `Dungeon_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `Characters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Logs`
--
ALTER TABLE `Logs`
  ADD CONSTRAINT `Logs_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `Characters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Logs_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Rooms`
--
ALTER TABLE `Rooms`
  ADD CONSTRAINT `Rooms_ibfk_1` FOREIGN KEY (`dungeon_id`) REFERENCES `Dungeon` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rooms_ibfk_2` FOREIGN KEY (`monster_temp_id`) REFERENCES `MonsterTemplate` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rooms_ibfk_3` FOREIGN KEY (`room_temp_id`) REFERENCES `RoomTemplate` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_room_template` FOREIGN KEY (`room_temp_id`) REFERENCES `RoomTemplate` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rooms_ibfk_4` FOREIGN KEY (`north_room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rooms_ibfk_5` FOREIGN KEY (`south_room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rooms_ibfk_6` FOREIGN KEY (`east_room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rooms_ibfk_7` FOREIGN KEY (`west_room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `RoomTemplate`
--
ALTER TABLE `RoomTemplate`
  ADD CONSTRAINT `RoomTemplate_ibfk_1` FOREIGN KEY (`monster_template`) REFERENCES `MonsterTemplate` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
