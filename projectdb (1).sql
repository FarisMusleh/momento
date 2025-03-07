-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 03:39 AM
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
-- Database: `projectdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `account-type` enum('user','business') NOT NULL,
  `provider` enum('local','google','facebook') NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `account-type`, `provider`, `provider_id`) VALUES
(1, 'faris', 'faris@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'local', NULL),
(3, 'ahmed', 'ahmed@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'local', NULL),
(4, 'khalid', 'khalid@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'local', NULL),
(5, 'sara', 'sara@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'local', NULL),
(10, 'test', 'farismusleh2032003@gmail.com', '', 'user', 'google', '113031318110845926527'),
(13, 'fff', 'farisassaf03@gmail.com', '', 'user', 'google', '115829613571035348563'),
(15, 'aaa', 'test@gmail.com', '$2y$10$fuZI1H4UiD9C8i0xEaKAcuUhLdAiwVHsm2MypdQyY6YxjTSA68g.q', 'business', 'local', NULL),
(16, 'sdf', 'ddsdsdd@gmaill.o', '$2y$10$zyWq0zkq7RTKXCv0unFRfOAmIRRi6hmCMHvA5jR/vMaeHl3gleV8u', 'user', 'local', NULL),
(17, 'fwf', 'farisss@gmail.com', '$2y$10$8HCOeHzSDrph0MabNhQ.geWGrnUM.6Zy0Jjxh9Se7Grohp/wka01e', 'user', 'local', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business-profiles`
--

CREATE TABLE `business-profiles` (
  `id` int(11) NOT NULL,
  `first-name` varchar(32) DEFAULT NULL,
  `last-name` varchar(32) DEFAULT NULL,
  `gender` enum('not specifed','male','female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone-num` int(10) DEFAULT NULL,
  `picture` varchar(512) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business-profiles`
--

INSERT INTO `business-profiles` (`id`, `first-name`, `last-name`, `gender`, `dob`, `phone-num`, `picture`, `title`, `description`) VALUES
(1, 'faris', 'musleh', 'male', '2003-03-20', 781231230, 'img/pfp/p5.jpeg', 'Computer Science', 'Best programmer, I use php to develop web apps.'),
(3, 'ahmed', 'AHMED', 'male', '2003-03-21', 781231230, 'img/pfp/p3.jpg', 'Software Engineer', 'abcefjngfdsngnjsadfnjsaklgnjdsnkjfasnjkdgnfkjdsnjgk nfjkdsngfjk njgsfd'),
(4, 'khalid', 'KHALID', 'male', '2003-03-22', 781231230, 'img/pfp/p1.jpg', 'Software Engineer', 'abcefjngfdsngnjsadfnjsaklgnjdsnkjfasnjkdgnfkjdsnjgk nfjkdsngfjk njgsfd'),
(5, 'sara', 'SARA', 'female', '2003-03-25', 781231230, 'img/pfp/p2.jpg', 'Software Engineer', 'abcefjngfdsngnjsadfnjsaklgnjdsnkjfasnjkdgnfkjdsnjgk nfjkdsngfjk njgsfd'),
(15, 'yaser', 'khalid', 'male', '2005-03-03', 32132132, 'img/pfp/p7.jpeg', 'dsadfdsfas', 'Hello fjdiasfngiagnfuidagbnusiaodfgbasdflasdbfg');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user-id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user-id`, `title`, `description`, `url`) VALUES
(1, 1, 'My first photo', 'gmhbikdfsfgsdfgasddfasf fadsf', 'uploads/d.jpg'),
(2, 1, 'My First Photo', 'fdasfdg dfgf dgfsadgv sag fd', 'uploads/C.jpg'),
(3, 1, '', '', 'uploads/OIP.jpeg'),
(4, 1, '', '', 'uploads/flowers-276014_640.jpg'),
(5, 3, '', '', 'uploads/flowers-276014_640.jpg'),
(6, 5, '', '', 'uploads/flowers-276014_640.jpg'),
(7, 4, '', '', 'uploads/flowers-276014_640.jpg'),
(8, 1, '', '', 'uploads/OIP.jpg'),
(9, 15, '', '', 'uploads/dragon-ball-z-round-black-white-wallpaper.jpg'),
(10, 1, '', '', 'uploads/Capture.PNG'),
(11, 1, '', '', 'uploads/Capture.PNG'),
(12, 1, '', '', 'uploads/199138.png');

-- --------------------------------------------------------

--
-- Table structure for table `user-profiles`
--

CREATE TABLE `user-profiles` (
  `id` int(11) NOT NULL,
  `first-name` varchar(32) DEFAULT NULL,
  `last-name` varchar(32) DEFAULT NULL,
  `gender` enum('not specified','male','female') DEFAULT 'not specified',
  `dob` date DEFAULT NULL,
  `phone-num` int(10) DEFAULT NULL,
  `picture` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user-profiles`
--

INSERT INTO `user-profiles` (`id`, `first-name`, `last-name`, `gender`, `dob`, `phone-num`, `picture`) VALUES
(16, NULL, NULL, 'male', NULL, NULL, NULL),
(17, NULL, NULL, 'male', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_col1_col2` (`email`,`provider`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique2_col1_col2` (`provider`,`provider_id`);

--
-- Indexes for table `business-profiles`
--
ALTER TABLE `business-profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user-profiles`
--
ALTER TABLE `user-profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user-profiles`
--
ALTER TABLE `user-profiles`
  ADD CONSTRAINT `id_fk` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
