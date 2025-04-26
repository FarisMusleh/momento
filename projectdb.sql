-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 09:46 PM
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
  `account_type` enum('user','business') NOT NULL,
  `picture` varchar(512) NOT NULL DEFAULT '/momento/Uploads/ProfilePicture/img_1.jpg',
  `location` varchar(128) NOT NULL,
  `lon` double(255,30) NOT NULL,
  `lat` double(255,30) NOT NULL,
  `provider` enum('local','google','facebook') NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `account_type`, `picture`, `location`, `lon`, `lat`, `provider`, `provider_id`, `created_at`, `updated_at`) VALUES
(65, 'faris', 'farisassaf03@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/img_680b6ebdb864d0.94335760.jpg', 'Aruba', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '115829613571035348563', '2025-04-25 11:14:27', '2025-04-25 11:15:09'),
(66, 'b', 'b@g.c', '$2y$10$48yEv9QbjYc2/VA9P4wmouV2ABymhkri.0vgp68M1LVdh/vMhtZum', 'user', '/momento/uploads/ProfilePicture/img_680b6efbd36388.53243767.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-25 11:15:55', '2025-04-25 11:16:11'),
(67, 'oshiro', 'farismusleh2032003@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/img_680b716346f959.12084887.png', 'Bhutan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '113031318110845926527', '2025-04-25 11:26:10', '2025-04-25 11:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(255) NOT NULL,
  `api_key` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_profiles`
--

CREATE TABLE `business_profiles` (
  `id` int(11) NOT NULL,
  `business_name` varchar(256) NOT NULL,
  `gender` enum('not specifed','male','female') DEFAULT 'not specifed',
  `contact_number` varchar(15) DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `bio` varchar(512) NOT NULL,
  `job` varchar(512) DEFAULT NULL,
  `rate` float NOT NULL DEFAULT 0,
  `total_likes` int(255) NOT NULL DEFAULT 0,
  `total_views` int(255) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_profiles`
--

INSERT INTO `business_profiles` (`id`, `business_name`, `gender`, `contact_number`, `social_links`, `bio`, `job`, `rate`, `total_likes`, `total_views`, `created_at`, `updated_at`) VALUES
(65, 'Faris', 'not specifed', NULL, NULL, '', NULL, 0, 0, 0, '2025-04-25 11:14:27', '2025-04-25 11:14:27'),
(67, '̇Oshiro', 'not specifed', NULL, NULL, '', NULL, 0, 0, 0, '2025-04-25 11:26:10', '2025-04-25 11:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `image_id` int(255) NOT NULL,
  `comment` varchar(2048) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(128) NOT NULL,
  `sender_Id` int(128) NOT NULL,
  `receiver_id` int(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `sender_Id`, `receiver_id`, `created_at`) VALUES
(11, 66, 65, '2025-04-25 11:16:54'),
(12, 67, 65, '2025-04-25 11:27:52'),
(13, 67, 1, '2025-04-25 19:43:36'),
(14, 66, 1, '2025-04-26 18:43:56'),
(15, 65, 1, '2025-04-26 18:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `from_date` varchar(18) NOT NULL,
  `to_date` varchar(18) NOT NULL,
  `work_place` varchar(128) NOT NULL,
  `job` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(128) NOT NULL,
  `user_id` int(128) NOT NULL,
  `business_id` int(128) NOT NULL,
  `followed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `label` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`label`)),
  `embedding` text NOT NULL,
  `description` text NOT NULL,
  `views` int(255) NOT NULL DEFAULT 0,
  `likes` int(255) NOT NULL DEFAULT 0,
  `file_name` varchar(512) DEFAULT NULL,
  `url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `label`, `embedding`, `description`, `views`, `likes`, `file_name`, `url`, `created_at`, `updated_at`) VALUES
(144, 65, '[\"\"]', '', '', 0, 1, 'img_680b6eccdfdf49.12738556.jpg', 'uploads/Images/img_680b6eccdfdf49.12738556.jpg', '2025-04-25 11:15:24', '2025-04-26 18:57:45'),
(145, 65, '[\"lion\",\"animal\",\"wildlife\",\"business\",\"natural language processing\"]', '', 'lion', 0, 1, 'img_680b703eb6b2d7.10311425.jpg', 'uploads/Images/img_680b703eb6b2d7.10311425.jpg', '2025-04-25 11:21:34', '2025-04-26 18:57:45'),
(148, 67, '[\"lion\",\"wildlife\",\"animal\",\"business\",\"natural language processing\"]', '', '', 0, 0, 'img_680bdafbcfcf27.86082050.jpg', 'uploads/Images/img_680bdafbcfcf27.86082050.jpg', '2025-04-25 18:56:59', '2025-04-26 19:02:02'),
(149, 65, '[\"gaza\",\"palestine\",\"zarqa\",\"egypt\",\"war\"]', '', 'gaza', 0, 1, 'img_680d2b5fe3ac19.84040041.jpg', 'uploads/Images/img_680d2b5fe3ac19.84040041.jpg', '2025-04-26 18:52:15', '2025-04-26 19:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `image_downloads`
--

CREATE TABLE `image_downloads` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `image_id` int(255) NOT NULL,
  `downloads` int(255) NOT NULL,
  `downloaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_id` int(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `image_id`, `created_at`) VALUES
(142, 67, 145, '2025-04-25 20:13:20'),
(143, 67, 144, '2025-04-25 20:23:30'),
(144, 65, 149, '2025-04-26 19:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(128) NOT NULL,
  `conversation_id` int(128) NOT NULL,
  `sender_id` int(128) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `seen_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `message`, `sent_at`, `seen`, `seen_at`) VALUES
(120, 11, 66, 'hello faris', '2025-04-25 11:16:54', 1, NULL),
(121, 11, 66, 'my name is b', '2025-04-25 11:16:58', 1, NULL),
(122, 11, 65, 'yeah hello fatis', '2025-04-25 11:17:21', 1, NULL),
(123, 11, 65, 'faris*', '2025-04-25 11:17:24', 1, NULL),
(124, 11, 65, 'I mean b', '2025-04-25 11:17:36', 1, NULL),
(125, 12, 67, 'hello', '2025-04-25 11:27:52', 1, NULL),
(126, 13, 67, 'hello', '2025-04-25 19:43:44', 0, NULL),
(127, 13, 67, 'fdsa fdsa', '2025-04-25 19:43:58', 0, NULL),
(128, 13, 67, 'fdsa', '2025-04-25 19:45:12', 0, NULL),
(129, 13, 67, 'fdsa', '2025-04-25 19:45:12', 0, NULL),
(130, 13, 67, 'fdas', '2025-04-25 19:45:13', 0, NULL),
(131, 13, 67, 'f dsa', '2025-04-25 19:45:13', 0, NULL),
(132, 13, 67, 'fdas', '2025-04-25 19:45:15', 0, NULL),
(133, 13, 67, 'fda', '2025-04-25 19:45:15', 0, NULL),
(134, 13, 67, 'fd as', '2025-04-25 19:45:16', 0, NULL),
(135, 13, 67, 'fd as', '2025-04-25 19:45:16', 0, NULL),
(136, 13, 67, 'df asf das', '2025-04-25 19:45:18', 0, NULL),
(137, 13, 67, 'fdas', '2025-04-25 19:45:20', 0, NULL),
(139, 13, 67, 'test', '2025-04-25 20:04:19', 0, NULL),
(140, 13, 67, 'gfsdgf s', '2025-04-25 20:15:06', 0, NULL),
(141, 13, 67, 'test', '2025-04-25 20:24:26', 0, NULL),
(142, 13, 67, 'test', '2025-04-25 20:24:29', 0, NULL),
(143, 13, 67, 'gfdsgfsd', '2025-04-25 23:37:52', 0, NULL),
(144, 12, 65, 'hello', '2025-04-26 18:44:16', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `gender` enum('not specified','male','female') DEFAULT 'not specified',
  `bio` text NOT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `name`, `gender`, `bio`, `social_links`, `created_at`, `updated_at`) VALUES
(66, NULL, 'male', '', NULL, '2025-04-25 11:15:55', '2025-04-25 11:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `id` int(255) NOT NULL,
  `image_id` int(255) DEFAULT NULL,
  `user_id` int(255) NOT NULL,
  `session_id` int(255) DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique2_col1_col2` (`provider`,`provider_id`);

--
-- Indexes for table `business_profiles`
--
ALTER TABLE `business_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convs_senderid_fk` (`sender_Id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_abc_userid_fk` (`user_id`),
  ADD KEY `likes_abc_imageid_fk` (`image_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_abc_conv` (`conversation_id`),
  ADD KEY `message_abc_sender` (`sender_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business_profiles`
--
ALTER TABLE `business_profiles`
  ADD CONSTRAINT `fk_account_b_id` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `convs_senderid_fk` FOREIGN KEY (`sender_Id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_abc_imageid_fk` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_abc_userid_fk` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `message_abc_conv` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_abc_sender` FOREIGN KEY (`sender_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_account_id` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `id_fk` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
