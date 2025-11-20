-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 12:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dhvcon`
--

-- --------------------------------------------------------

--
-- Table structure for table `claimed_certificate`
--

CREATE TABLE `claimed_certificate` (
  `claimed_id` int(11) NOT NULL,
  `claimed_meeting_id` int(11) NOT NULL,
  `claimed_user_id` int(11) NOT NULL,
  `claimed_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classwork`
--

CREATE TABLE `classwork` (
  `classwork_id` int(11) NOT NULL,
  `classwork_title` varchar(60) NOT NULL,
  `classwork_instruction` text NOT NULL,
  `classwork_file` varchar(255) DEFAULT NULL,
  `classwork_by_user_id` int(11) NOT NULL,
  `classwork_room_id` int(11) NOT NULL,
  `classwork_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=archived,1=active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classwork`
--

INSERT INTO `classwork` (`classwork_id`, `classwork_title`, `classwork_instruction`, `classwork_file`, `classwork_by_user_id`, `classwork_room_id`, `classwork_status`, `created_at`) VALUES
(17, 'dawdaw ssss', 'ersfsreg', 'classwork_68f7c05b821b24.57595105.avif', 15, 33, 0, '2025-10-21 17:21:35'),
(18, 'task 2', 'dawdaw', 'classwork_68f7c03a499832.64730949.webp', 15, 33, 0, '2025-10-21 17:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `meeting_id` int(11) NOT NULL,
  `meeting_link` text NOT NULL,
  `meeting_title` varchar(60) NOT NULL,
  `meeting_description` text NOT NULL,
  `meeting_start` datetime NOT NULL,
  `meeting_end` datetime NOT NULL,
  `meeting_room_id` int(11) NOT NULL,
  `meeting_creator_user_id` int(11) NOT NULL,
  `meeting_pass` varchar(30) NOT NULL,
  `meeting_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=close,1=open',
  `rating` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`meeting_id`, `meeting_link`, `meeting_title`, `meeting_description`, `meeting_start`, `meeting_end`, `meeting_room_id`, `meeting_creator_user_id`, `meeting_pass`, `meeting_status`, `rating`) VALUES
(9, 'MTG-VJWB1N', 'test 111', 'awdawd', '2025-10-29 20:46:00', '2025-10-29 21:46:00', 33, 15, '56f6f028', 0, 0.0),
(10, 'MTG-FHQT98', 'DHVCON testing', 'awdawd', '2025-10-29 20:54:00', '2025-10-29 21:54:00', 33, 15, '707ce748', 0, 2.5),
(22, 'MTG-7GQBPR', 'test mailer 2', 'test', '2025-11-06 23:07:00', '2025-11-06 12:07:00', 33, 15, 'b8e0f3f9', 1, 0.0),
(24, 'MTG-JK5XPJ', 'test mailer 2', '3wrw3r', '2025-11-06 23:15:00', '2025-11-06 12:15:00', 33, 15, '900ba635', 0, 0.0),
(25, 'MTG-F5GGLI', 'test', 'fesfse', '2025-11-13 01:30:00', '2025-11-14 01:30:00', 33, 15, '95ea59aa', 1, 0.0);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_chats`
--

CREATE TABLE `meeting_chats` (
  `chat_id` int(11) NOT NULL,
  `chat_message` text NOT NULL,
  `chat_sender` int(11) NOT NULL,
  `chat_meeting_code` varchar(60) NOT NULL,
  `chat_type` enum('txt','img','document') NOT NULL DEFAULT 'txt',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_chats`
--

INSERT INTO `meeting_chats` (`chat_id`, `chat_message`, `chat_sender`, `chat_meeting_code`, `chat_type`, `created_at`) VALUES
(1, 'rwar', 15, 'MTG-FHQT98', 'txt', '2025-10-29 14:16:41'),
(2, '❌ Failed to join meeting: Cannot read properties of undefined (reading \'getUserMedia\')', 17, 'MTG-VJWB1N', 'txt', '2025-10-29 14:28:15'),
(3, 'sdsd', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:11'),
(4, 'as', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:15'),
(5, 'wd', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:16'),
(6, 'sef', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:17'),
(7, 'rg', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:18'),
(8, 'th', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:19'),
(9, 'yj', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:20'),
(10, 'uk', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:21'),
(11, 'uil', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:02:22'),
(12, 'awdawd', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:04:17'),
(13, 'drgdrg', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:04:22'),
(14, 'ssawdawd', 15, 'MTG-FHQT98', 'txt', '2025-10-29 15:24:03'),
(15, 'hello', 17, 'MTG-FHQT98', 'txt', '2025-10-29 15:29:52'),
(16, 'ldkdkdnd', 17, 'MTG-FHQT98', 'txt', '2025-10-29 15:40:33'),
(17, 'yyy', 17, 'MTG-FHQT98', 'txt', '2025-10-29 15:41:48'),
(18, 'hhh', 17, 'MTG-FHQT98', 'txt', '2025-10-29 15:41:51'),
(19, 'test', 15, 'MTG-7GQBPR', 'txt', '2025-11-11 01:27:58'),
(20, 'test', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:12:46'),
(21, 'test', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:12:56'),
(22, 'ff', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:13:01'),
(23, 'fef', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:13:14'),
(24, 'ss', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:13:16'),
(25, 'xx', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:13:26'),
(26, 'o', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:13:41'),
(27, 'hey', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:53:47'),
(28, '33', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:56:28'),
(29, '44', 15, 'MTG-7GQBPR', 'txt', '2025-11-12 04:56:31'),
(30, '🙄😅✌️😅✌️😅😅', 15, 'MTG-JK5XPJ', 'txt', '2025-11-12 16:27:43'),
(31, 'test jane here', 22, 'MTG-7GQBPR', 'txt', '2025-11-12 16:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_logs`
--

CREATE TABLE `meeting_logs` (
  `ml_id` int(11) NOT NULL,
  `ml_user_id` int(11) NOT NULL,
  `ml_date_joined` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ml_meeting_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_logs`
--

INSERT INTO `meeting_logs` (`ml_id`, `ml_user_id`, `ml_date_joined`, `ml_meeting_id`) VALUES
(34, 15, '2025-10-29 12:58:58', 9),
(35, 15, '2025-10-29 13:08:16', 10),
(38, 16, '2025-11-02 12:51:18', 10);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_member`
--

CREATE TABLE `meeting_member` (
  `jr_id` int(11) NOT NULL,
  `jr_meeting_id` int(11) NOT NULL,
  `jr_user_id` int(11) DEFAULT NULL,
  `jr_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `jr_requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_member`
--

INSERT INTO `meeting_member` (`jr_id`, `jr_meeting_id`, `jr_user_id`, `jr_status`, `jr_requested_at`) VALUES
(34, 22, 22, 'approved', '2025-11-11 02:31:04');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_ratings`
--

CREATE TABLE `meeting_ratings` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_ratings`
--

INSERT INTO `meeting_ratings` (`id`, `meeting_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(13, 10, 22, 2, 'rtest', '2025-11-05 14:25:58', '2025-11-05 14:25:58'),
(14, 10, 16, 3, 'ss', '2025-11-05 14:29:25', '2025-11-05 14:54:22');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_creator_user_id` int(11) NOT NULL,
  `room_banner` varchar(255) DEFAULT NULL,
  `room_code` varchar(60) NOT NULL,
  `room_name` varchar(60) NOT NULL,
  `room_description` text NOT NULL,
  `room_status` int(11) NOT NULL DEFAULT 1,
  `room_date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_creator_user_id`, `room_banner`, `room_code`, `room_name`, `room_description`, `room_status`, `room_date_created`) VALUES
(33, 15, 'room_68f7bb9ee735f8.18881901.webp', 'NU9OFC', 'room 108', 'The BS Information Technology (BSIT) program includes the study of the utilization of both hardware and software technologies involving planning, installing, customizing, operating, managing and administering, and maintaining information technology infrastructure that provides computing solutions to address the needs of an organization.\r\n\r\nThe program prepares graduates to address various user needs involving the selection, development, application, integration, and management of computing technologies within an organization. * Program description is lifted from the CHED Memorandum Order No. 25 s.2015', 1, '2025-11-12 16:58:36'),
(34, 15, 'room_6914bf7ce8e809.95413699.jpg', 'U264VA', 'room 101', 'awda', 1, '2025-11-12 17:10:20'),
(35, 15, 'room_6914c0409ce664.97220938.jpg', 'MWH2PK', 'test', 'vddvd', 1, '2025-11-12 17:17:26'),
(36, 15, 'room_6914c09985ec78.70922314.png', '6S5GC1', 'geg', 'fthft', 1, '2025-11-13 09:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `room_members`
--

CREATE TABLE `room_members` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_members`
--

INSERT INTO `room_members` (`id`, `room_id`, `user_id`, `date_joined`) VALUES
(33, 33, 16, '2025-10-29 12:05:27'),
(36, 35, 22, '2025-11-12 17:25:29'),
(37, 33, 22, '2025-11-12 17:25:38');

-- --------------------------------------------------------

--
-- Table structure for table `submitted_classwork`
--

CREATE TABLE `submitted_classwork` (
  `sw_id` int(11) NOT NULL,
  `sw_classwork_id` int(11) NOT NULL,
  `sw_user_id` int(11) NOT NULL,
  `sw_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sw_files`)),
  `sw_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=not-turnin,1=turnin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(60) NOT NULL,
  `user_email` varchar(60) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_type` enum('admin','teacher','student','') NOT NULL,
  `user_requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user_requirements`)),
  `user_profile_pict` varchar(255) DEFAULT NULL,
  `user_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=for-approval,1=active,2=disabled',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fullname`, `user_email`, `user_password`, `user_type`, `user_requirements`, `user_profile_pict`, `user_status`, `reset_token`, `reset_expiry`) VALUES
(1, 'admin de la cruzss', 'admin@gmail.com', '$2y$10$A1uALnxcGXc2Bo.APqZyRub3JPK/yuQytdrEzBE79vwcV.XQip1Te', 'admin', NULL, NULL, 1, NULL, NULL),
(15, 'joshua padilla', 'andersonandy046@gmail.com', '$2y$10$XSPraUmPB0CrRtZ05pYrh.tDFy3zSyyYcIIdge2/bFc0lmwLJ2lVy', 'teacher', NULL, 'profile_6914b93159ec80.30504779.jpg', 1, '7b233409e9f5146f436dc6bddbd48fe8', 1763381011),
(16, 'san jose', 'masterparj@gmail.com', '$2y$10$jwEF4zulPduxjA28gqgRS.8ZfWeFrp03blGD9PGIHWO57bEKWiJM.', 'student', NULL, NULL, 1, NULL, NULL),
(22, 'april jane', 'padillajoshuaanderson.pdm@gmail.com', '$2y$10$lrrhk5bEA6IRcYxPEI9KheHuA8TYaPoKj.K8ZDJG0A3uGTk2Kn03a', 'student', '[\"6907778db0bd4_bini-desktop-wallpapers-v0-v1z43kmivtbd1.webp\",\"6907778db0e6b_dbd95cee-40e9-420e-a3f0-0b63d7073197.webp\",\"6907778db1176_Exercise-04.docs.pdf\"]', 'profile_6909c07c45a1c5.44233590.webp', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `claimed_certificate`
--
ALTER TABLE `claimed_certificate`
  ADD PRIMARY KEY (`claimed_id`),
  ADD KEY `claimed_meeting_id` (`claimed_meeting_id`),
  ADD KEY `claimed_user_id` (`claimed_user_id`);

--
-- Indexes for table `classwork`
--
ALTER TABLE `classwork`
  ADD PRIMARY KEY (`classwork_id`),
  ADD KEY `classword_by_user_id` (`classwork_by_user_id`),
  ADD KEY `classwork_room_id` (`classwork_room_id`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meeting_id`),
  ADD KEY `meeting_room_id` (`meeting_room_id`),
  ADD KEY `meeting_creator_user_id` (`meeting_creator_user_id`);

--
-- Indexes for table `meeting_chats`
--
ALTER TABLE `meeting_chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `idx_sender` (`chat_sender`),
  ADD KEY `idx_receiver` (`chat_meeting_code`);

--
-- Indexes for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  ADD PRIMARY KEY (`ml_id`),
  ADD KEY `ml_meeting_id` (`ml_meeting_id`),
  ADD KEY `ml_user_id` (`ml_user_id`);

--
-- Indexes for table `meeting_member`
--
ALTER TABLE `meeting_member`
  ADD PRIMARY KEY (`jr_id`),
  ADD KEY `jr_user_id` (`jr_user_id`),
  ADD KEY `jr_meeting_id` (`jr_meeting_id`);

--
-- Indexes for table `meeting_ratings`
--
ALTER TABLE `meeting_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_meeting_user` (`meeting_id`,`user_id`),
  ADD KEY `fk_meeting_rating_user` (`user_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `room_creator_user_id` (`room_creator_user_id`);

--
-- Indexes for table `room_members`
--
ALTER TABLE `room_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_user` (`room_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `submitted_classwork`
--
ALTER TABLE `submitted_classwork`
  ADD PRIMARY KEY (`sw_id`),
  ADD KEY `sw_classwork_id` (`sw_classwork_id`),
  ADD KEY `sw_user_id` (`sw_user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `claimed_certificate`
--
ALTER TABLE `claimed_certificate`
  MODIFY `claimed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `classwork`
--
ALTER TABLE `classwork`
  MODIFY `classwork_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `meeting_chats`
--
ALTER TABLE `meeting_chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  MODIFY `ml_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `meeting_member`
--
ALTER TABLE `meeting_member`
  MODIFY `jr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `meeting_ratings`
--
ALTER TABLE `meeting_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `room_members`
--
ALTER TABLE `room_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `submitted_classwork`
--
ALTER TABLE `submitted_classwork`
  MODIFY `sw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `claimed_certificate`
--
ALTER TABLE `claimed_certificate`
  ADD CONSTRAINT `claimed_certificate_ibfk_1` FOREIGN KEY (`claimed_meeting_id`) REFERENCES `meeting` (`meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `claimed_certificate_ibfk_2` FOREIGN KEY (`claimed_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classwork`
--
ALTER TABLE `classwork`
  ADD CONSTRAINT `classwork_ibfk_1` FOREIGN KEY (`classwork_by_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classwork_ibfk_2` FOREIGN KEY (`classwork_room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`meeting_room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_2` FOREIGN KEY (`meeting_creator_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  ADD CONSTRAINT `meeting_logs_ibfk_1` FOREIGN KEY (`ml_meeting_id`) REFERENCES `meeting` (`meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_logs_ibfk_2` FOREIGN KEY (`ml_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meeting_member`
--
ALTER TABLE `meeting_member`
  ADD CONSTRAINT `meeting_member_ibfk_1` FOREIGN KEY (`jr_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_member_ibfk_2` FOREIGN KEY (`jr_meeting_id`) REFERENCES `meeting` (`meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meeting_ratings`
--
ALTER TABLE `meeting_ratings`
  ADD CONSTRAINT `fk_meeting_rating_meeting` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`meeting_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_meeting_rating_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`room_creator_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_members`
--
ALTER TABLE `room_members`
  ADD CONSTRAINT `room_members_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `submitted_classwork`
--
ALTER TABLE `submitted_classwork`
  ADD CONSTRAINT `submitted_classwork_ibfk_1` FOREIGN KEY (`sw_classwork_id`) REFERENCES `classwork` (`classwork_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submitted_classwork_ibfk_2` FOREIGN KEY (`sw_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
