-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 05:04 PM
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
-- Table structure for table `classwork`
--

CREATE TABLE `classwork` (
  `classwork_id` int(11) NOT NULL,
  `classwork_title` varchar(60) NOT NULL,
  `classwork_instruction` text NOT NULL,
  `classwork_file` varchar(255) DEFAULT NULL,
  `classwork_by_user_id` int(11) NOT NULL,
  `classwork_room_id` int(11) NOT NULL,
  `classwork_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=archived,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classwork`
--

INSERT INTO `classwork` (`classwork_id`, `classwork_title`, `classwork_instruction`, `classwork_file`, `classwork_by_user_id`, `classwork_room_id`, `classwork_status`) VALUES
(4, 'assignment 1', 'awdawd', 'classwork_68e7c2dfa75215.91300019.pdf', 3, 10, 1),
(5, 'assignment 2', 'awdaw', 'classwork_68e7c916a3f775.14889696.jpg', 3, 10, 1);

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
  `room_date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_creator_user_id`, `room_banner`, `room_code`, `room_name`, `room_description`, `room_date_created`) VALUES
(9, 2, 'room_68e7b0f7623f87.48263628.webp', 'HC8WJV', 'room 101', 'Empowering collaboration and innovation — earn your certificate through our official platform.', '2025-10-09 13:26:32'),
(10, 2, 'room_68e7b105930182.58150215.webp', 'OIVBY2', 'room 102', 'A creative workspace designed for brainstorming and collaboration. Equipped with digital whiteboards, cozy seating, and a modern aesthetic perfect for team discussions or workshops.', '2025-10-09 13:27:36'),
(11, 3, 'room_68e7b135503632.26898286.webp', '1TAFV4', 'room 102', 'A calm and relaxing environment ideal for study sessions or casual hangouts. Soft lighting, minimalist décor, and ambient music make it a peaceful spot to focus or unwind.', '2025-10-09 13:27:41'),
(12, 3, 'room_68e7b150e50f32.70005846.png', 'UOKQFX', 'room 103', 'Where players unite! This room is built for gaming enthusiasts — featuring competitive challenges, tournaments, and friendly banter. Grab your controller and join the fun.', '2025-10-09 13:27:47'),
(13, 3, 'room_68e7b17648be30.74494203.jpg', 'QN4CF2', 'room 104', 'A focused, distraction-free room for students and professionals alike. With access to shared notes, project discussions, and helpful resources, productivity comes naturally here.', '2025-10-09 13:27:53'),
(14, 3, 'room_68e7b1888a2761.92219144.webp', 'WD1CB3', 'room 105', 'A community space for artists, designers, and content creators to share ideas, showcase projects, and collaborate on creative ventures. Inspiration starts the moment you join.', '2025-10-09 13:27:59');

-- --------------------------------------------------------

--
-- Table structure for table `room_members`
--

CREATE TABLE `room_members` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_members`
--

INSERT INTO `room_members` (`id`, `room_id`, `user_id`) VALUES
(6, 9, 3),
(5, 10, 3),
(7, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `submitted_classwork`
--

CREATE TABLE `submitted_classwork` (
  `sw_id` int(11) NOT NULL,
  `sw_classwork_id` int(11) NOT NULL,
  `sw_user_id` int(11) NOT NULL,
  `sw_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=undone,1=done'
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
  `user_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=not-active,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fullname`, `user_email`, `user_password`, `user_status`) VALUES
(1, 'Joshua Anderson Padilla', 'joshua@gmail.com', '$2y$10$yeFySWND5eJqX4meEdCnw.D9AbHTMLRuI8W1.26dD4vujD8KrU1bC', 1),
(2, 'john john', 'john@gmail.com', '$2y$10$FF11SoWjaBpdpaE6oEycfe2Gh9QmQLsfecQ0x/Viv4mBQVqXcjAIy', 1),
(3, 'Joshua Anderson Padilla', 'andersonandy046@gmail.com', '$2y$10$rxa/yT1Cx9EhWyRhnB8l1O213zkUBiaaNw/qaoaHo5H2Np/MCFmv6', 1),
(4, 'samantha flores', 'samantha123@gmail.com', '$2y$10$f9XLVW/ETL/uMDZ2z/YIHOlRwqFm7.ajG6Yf3kWngyPiBYLDa8BNS', 1),
(5, 'john doe', 'jdoe@gmail.com', '$2y$10$xVtSGeRcTRn8wAg.2VLXXOxCGL9wCDWFkxkOZM41xPCscqnU7/GxC', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classwork`
--
ALTER TABLE `classwork`
  ADD PRIMARY KEY (`classwork_id`),
  ADD KEY `classword_by_user_id` (`classwork_by_user_id`),
  ADD KEY `classwork_room_id` (`classwork_room_id`);

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
-- AUTO_INCREMENT for table `classwork`
--
ALTER TABLE `classwork`
  MODIFY `classwork_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `room_members`
--
ALTER TABLE `room_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `submitted_classwork`
--
ALTER TABLE `submitted_classwork`
  MODIFY `sw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classwork`
--
ALTER TABLE `classwork`
  ADD CONSTRAINT `classwork_ibfk_1` FOREIGN KEY (`classwork_by_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classwork_ibfk_2` FOREIGN KEY (`classwork_room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
