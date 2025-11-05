-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 03:37 PM
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
(10, 'MTG-FHQT98', 'DHVCON testing', 'awdawd', '2025-10-29 20:54:00', '2025-10-29 21:54:00', 33, 15, '707ce748', 0, 3.0),
(18, 'MTG-B5J68Y', 'test mailer', 'ad', '2025-11-03 21:54:00', '2025-11-04 21:54:00', 33, 15, '883c3878', 1, 0.0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meeting_id`),
  ADD KEY `meeting_room_id` (`meeting_room_id`),
  ADD KEY `meeting_creator_user_id` (`meeting_creator_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`meeting_room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_2` FOREIGN KEY (`meeting_creator_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
