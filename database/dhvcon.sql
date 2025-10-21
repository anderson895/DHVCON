-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2025 at 03:01 PM
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
(31, 1, 'room_68eb5d944d6685.57757154.jpg', 'WXZ1E5', 'Mobile Development', 'Mobile apps have taken a remarkable rise in the tech market. App stores are overflowing with various kinds of applications. Several businesses have stepped forward in developing their mobile applications to expand their business and achieve success. Mobile applications have become an integral part of people’s lives, which has inspired enterprises to come up with their apps to satisfy their customers’ requirements.\r\n\r\nWhen you plan to develop your mobile app, it is important that you integrate unique features and functionality to make it stand out in the market. Also, it is significant to see that it does not become a common app similar to others. Your mobile app has to have the potential to attract new customers and retain the existing ones as well.', 1, '2025-10-12 07:49:40');

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
  `user_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=not-active,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fullname`, `user_email`, `user_password`, `user_type`, `user_requirements`, `user_status`) VALUES
(1, 'admin de la cruz', 'admin@gmail.com', '$2y$10$fF5ASoYxcMYvG5bawBNfkusgRzUQwgB5xXYbqJubQFYrn6uwl4.l2', 'admin', NULL, 1),
(15, 'Joshua Anderson Padilla', 'andersonandy046@gmail.com', '$2y$10$8aFvB1j42iN3RAjRRA9g3e/Z.f9qm1uaKWEKa3Y5I73GUY2.H/gUy', 'teacher', '[\"68f7840d267d0_492151840_3128831320602859_4159043562509539743_n.jpg\",\"68f7840d26a4f_494579798_693373853415594_8083316453582063649_n.jpg\",\"68f7840d26c47_494820713_532984179748342_8788347790273388241_n.png\"]', 0);

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
-- Indexes for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  ADD PRIMARY KEY (`ml_id`),
  ADD KEY `ml_meeting_id` (`ml_meeting_id`),
  ADD KEY `ml_user_id` (`ml_user_id`);

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
  MODIFY `classwork_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  MODIFY `ml_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `room_members`
--
ALTER TABLE `room_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `submitted_classwork`
--
ALTER TABLE `submitted_classwork`
  MODIFY `sw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
