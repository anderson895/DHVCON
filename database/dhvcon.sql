-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 08:12 PM
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
  `meeting_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=close,1=open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`meeting_id`, `meeting_link`, `meeting_title`, `meeting_description`, `meeting_start`, `meeting_end`, `meeting_room_id`, `meeting_creator_user_id`, `meeting_pass`, `meeting_status`) VALUES
(9, 'MTG-VJWB1N', 'test 111', 'awdawd', '2025-10-29 20:46:00', '2025-10-29 21:46:00', 33, 15, '56f6f028', 0),
(10, 'MTG-FHQT98', 'DHVCON testing', 'awdawd', '2025-10-29 20:54:00', '2025-10-29 21:54:00', 33, 15, '707ce748', 0),
(12, 'MTG-PCT1C6', 'meeting 1', 'awdawd', '2025-11-02 23:34:00', '2025-11-02 14:34:00', 33, 15, 'f4072d29', 1);

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
(18, 'hhh', 17, 'MTG-FHQT98', 'txt', '2025-10-29 15:41:51');

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
(38, 16, '2025-11-02 12:51:18', 10),
(39, 22, '2025-11-02 15:34:52', 12),
(40, 15, '2025-11-02 15:35:50', 12);

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
(25, 12, 22, 'approved', '2025-11-02 18:51:04');

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
(31, 1, 'room_68eb5d944d6685.57757154.jpg', 'WXZ1E5', 'Mobile Development', 'Mobile apps have taken a remarkable rise in the tech market. App stores are overflowing with various kinds of applications. Several businesses have stepped forward in developing their mobile applications to expand their business and achieve success. Mobile applications have become an integral part of people’s lives, which has inspired enterprises to come up with their apps to satisfy their customers’ requirements.\r\n\r\nWhen you plan to develop your mobile app, it is important that you integrate unique features and functionality to make it stand out in the market. Also, it is significant to see that it does not become a common app similar to others. Your mobile app has to have the potential to attract new customers and retain the existing ones as well.', 1, '2025-10-12 07:49:40'),
(32, 15, 'room_68f7b194a15705.71072707.avif', '1XCQ7O', 'drhthtf', 'gyjgy', 0, '2025-10-21 16:15:39'),
(33, 15, 'room_68f7bb9ee735f8.18881901.webp', 'NU9OFC', 'room 102', 'awdawd', 1, '2025-10-21 16:58:06');

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
(32, 31, 15, '2025-10-21 16:57:57'),
(33, 33, 16, '2025-10-29 12:05:27'),
(35, 33, 22, '2025-11-02 15:34:36');

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
  `user_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=for-approval,1=active,2=disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fullname`, `user_email`, `user_password`, `user_type`, `user_requirements`, `user_status`) VALUES
(1, 'admin de la cruzss', 'admin@gmail.com', '$2y$10$A1uALnxcGXc2Bo.APqZyRub3JPK/yuQytdrEzBE79vwcV.XQip1Te', 'admin', NULL, 1),
(15, 'joshua padilla', 'andersonandy046@gmail.com', '$2y$10$aaV7.u070wWhP2QZyd55EO/2ScxmADcC6V0xa.rZxb07KYbNtNf46', 'teacher', NULL, 1),
(16, 'san jose', 'sanjosekylie@yahoo.com', '$2y$10$SkVHrwVre/pLy.qmOcMotuS8oGa7Gzj6PampHpT1HMbrPMPxXK8jm', 'student', NULL, 1),
(22, 'april jane', 'padillajoshuaanderson.pdm@gmail.com', '$2y$10$lrrhk5bEA6IRcYxPEI9KheHuA8TYaPoKj.K8ZDJG0A3uGTk2Kn03a', 'student', '[\"6907778db0bd4_bini-desktop-wallpapers-v0-v1z43kmivtbd1.webp\",\"6907778db0e6b_dbd95cee-40e9-420e-a3f0-0b63d7073197.webp\",\"6907778db1176_Exercise-04.docs.pdf\"]', 1);

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
  MODIFY `claimed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classwork`
--
ALTER TABLE `classwork`
  MODIFY `classwork_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `meeting_chats`
--
ALTER TABLE `meeting_chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  MODIFY `ml_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `meeting_member`
--
ALTER TABLE `meeting_member`
  MODIFY `jr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `room_members`
--
ALTER TABLE `room_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
