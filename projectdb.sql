-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2025 at 12:43 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_related_entries` (IN `delete_id` INT)   BEGIN
    -- Delete from table2 first
    DELETE FROM business_profiles WHERE id = delete_id;

    -- Delete from table1
    DELETE FROM accounts WHERE id = delete_id;
END$$

DELIMITER ;

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
  `picture` varchar(512) NOT NULL DEFAULT 'Uploads/ProfilePicture/img_1.jpg',
  `location` varchar(128) NOT NULL,
  `provider` enum('local','google','facebook') NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `account_type`, `picture`, `location`, `provider`, `provider_id`, `created_at`, `updated_at`) VALUES
(30, 'b', 'b@g.c', '$2y$10$ff.o2apGdBxJMK.95p/5PefpkQE8GZg7mVGz5qnIfUyR5E8DJLZwG', 'business', 'uploads/ProfilePicture/img_67faab0d7c63e0.24795740.jpg', '', 'local', NULL, '2025-03-20 13:59:39', '2025-04-12 18:03:57'),
(42, 'TEST', 'farisassaf03@gmail.com', '', 'business', 'uploads/ProfilePicture/img_67f85673438969.99995404.jpg', '', 'google', '115829613571035348563', '2025-04-10 13:05:32', '2025-04-10 23:38:27'),
(43, 'faris', 'a@g.c', '$2y$10$XoV0EHGKPhUjmZu4psviO.yJz401P3pC8AgsylUOSgnb.HS9NMX8C', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:16:52', '2025-04-11 23:16:52'),
(44, 'ahmed', 'c@g.c', '$2y$10$2iDXBE/6duAZAVXJLJsKC.l8F8U31txrJxzutju93mgcfT0vZO3G.', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:17:35', '2025-04-11 23:17:35'),
(45, 'r', 'h@c.g', '$2y$10$zn/mFGXBv5KplnQU9eDNlu2bk2SLxp9mZwZYfOkZuOQCfH4t2Yk2C', 'user', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:17:51', '2025-04-11 23:17:51'),
(46, 'te', 'h@h.c', '$2y$10$Vd7pV1Ens83dy80hcDAd4..GiZIt0TNqSovrLDBPS.c.8PS.P6MhS', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:18:08', '2025-04-11 23:18:08'),
(47, 'y', 'y@y.c', '$2y$10$z5g3NRJRSu754pEJ7YVbz.55KUVA5Sl6.muZMhoTH3DqPH2qiMCJi', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:18:22', '2025-04-11 23:18:22'),
(48, 'p', 'i@g.c', '$2y$10$1eDTLo85wGqYupdVbqckgOMlHrQX4nuWXkHB.6AiEcKlT1nhEYBxC', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:18:38', '2025-04-11 23:18:38'),
(49, 'ax', 'l@g.c', '$2y$10$YNaet2QgU7qt.VtwZDdvteRaJyIyE6pdbWrtsR6qhQ6LxQhX/lZDC', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:19:22', '2025-04-11 23:19:22'),
(50, 'hj', 'hj@g.c', '$2y$10$Q31aPHgG1SN7P6eacjilZuyAn2e3gw/5VveU/3hq1EHj9JHrfehje', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:19:34', '2025-04-11 23:19:34'),
(51, 'll', 'dsa@g.c', '$2y$10$YzE2Wf7.LHCLbAkX0mSrMuI9srmpV5cdSi.9ZyBuZQj7SL5uldEuS', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 'local', NULL, '2025-04-11 23:19:46', '2025-04-11 23:19:46'),
(56, 'farismusleh', 'farismusleh2032003@gmail.com', '', 'business', 'uploads/ProfilePicture/img_67fadfcb769720.85158432.jpg', 'Argentina', 'google', '113031318110845926527', '2025-04-12 21:48:07', '2025-04-12 21:48:59');

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
  `rate` int(2) NOT NULL DEFAULT 0,
  `total_likes` int(255) NOT NULL DEFAULT 0,
  `total_views` int(255) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_profiles`
--

INSERT INTO `business_profiles` (`id`, `business_name`, `gender`, `contact_number`, `social_links`, `bio`, `rate`, `total_likes`, `total_views`, `created_at`, `updated_at`) VALUES
(30, '', 'male', NULL, '{\"facebook\":\"https:\\/\\/web.facebook.com\\/\",\"twitter\":\"\",\"linked-in\":\"\",\"instagram\":\"\"}', '', 0, 0, 0, '2025-03-20 13:59:39', '2025-04-12 18:01:50'),
(42, 'Faris', 'not specifed', NULL, '{\"facebook\":\"https:\\/\\/web.facebook.com\\/\",\"twitter\":\"https:\\/\\/web.facebook.com\\/\",\"linked-in\":\"https:\\/\\/web.facebook.com\\/\",\"instagram\":\"https:\\/\\/web.facebook.com\\/\"}', '', 0, 0, 0, '2025-04-10 13:05:32', '2025-04-11 00:12:30'),
(43, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:16:52', '2025-04-11 23:16:52'),
(44, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:17:35', '2025-04-11 23:17:35'),
(46, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:18:08', '2025-04-11 23:18:08'),
(47, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:18:22', '2025-04-11 23:18:22'),
(48, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:18:38', '2025-04-11 23:18:38'),
(49, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:19:22', '2025-04-11 23:19:22'),
(56, '̇Oshiro', 'not specifed', NULL, NULL, '', 0, 0, 0, '2025-04-12 21:48:07', '2025-04-12 21:48:07');

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
  `user_id` int(128) NOT NULL,
  `business_id` int(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `label` varchar(512) NOT NULL,
  `embedding` text NOT NULL,
  `description` text NOT NULL,
  `views` int(255) NOT NULL DEFAULT 0,
  `likes` int(255) NOT NULL DEFAULT 0,
  `url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `label`, `embedding`, `description`, `views`, `likes`, `url`, `created_at`, `updated_at`) VALUES
(100, 30, 'anime, journalist, animation', '', '', 0, 3, 'uploads/Images/img_67df97e44460a4.02441688.jpg', '2025-03-23 05:11:00', '2025-04-11 14:58:58'),
(101, 30, 'architecture, travel, kuwait, cars', '', '', 0, 1, 'uploads/Images/img_67f58dc7c33d96.76963262.png', '2025-04-08 20:57:43', '2025-04-11 13:17:24'),
(102, 42, 'graduation, wedding', '', 'idk', 0, 5, 'uploads/Images/img_67f7d7cf03f8d2.85822539.jpg', '2025-04-10 14:38:07', '2025-04-12 17:35:51'),
(103, 42, 'pets, food, style, gaza, egypt', '', 'l', 0, 3, 'uploads/Images/img_67f7d870d4b772.81008854.jpg', '2025-04-10 14:40:48', '2025-04-11 12:25:42'),
(104, 42, 'palestine', '', 'p', 0, 18, 'uploads/Images/img_67f7d9fbd6d462.91561722.png', '2025-04-10 14:47:23', '2025-04-12 19:26:04'),
(105, 42, 'egypt, men, tourism, style, food', '', 'lion :)', 0, 3, 'uploads/Images/img_67f7ea1447f439.48649551.jpg', '2025-04-10 15:56:04', '2025-04-12 21:12:42'),
(106, 42, 'travel, egypt, tourism, events', '', 'ba', 0, 0, 'uploads/Images/img_67f7ea291e1648.08642139.jpg', '2025-04-10 15:56:25', '2025-04-10 15:56:25'),
(107, 42, 'pets, sports', '', 'dog', 0, 2, 'uploads/Images/img_67f7eb147f13c1.25239980.jpg', '2025-04-10 16:00:20', '2025-04-11 23:08:44'),
(108, 42, 'nature, travel, ukraine', '', 'test', 0, 3, 'uploads/Images/img_67f853545ccec7.36154339.jpg', '2025-04-10 23:25:08', '2025-04-12 18:35:39'),
(109, 54, 'egypt, men, tourism, style, food', '', 'lion', 0, 0, 'uploads/Images/img_67facb11d48503.44093740.jpg', '2025-04-12 20:20:33', '2025-04-12 20:20:33'),
(110, 55, 'egypt, men, tourism, style, food', '', 'test', 0, 0, 'uploads/Images/img_67fadf02b60f43.25272949.jpg', '2025-04-12 21:45:38', '2025-04-12 21:45:38');

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
(61, 42, 104, '2025-04-11 11:06:08'),
(71, 30, 104, '2025-04-11 11:23:11'),
(77, 30, 107, '2025-04-11 12:01:41'),
(80, 30, 103, '2025-04-11 12:25:41'),
(86, 42, 102, '2025-04-11 14:30:34'),
(88, 42, 100, '2025-04-11 14:58:58'),
(90, 42, 108, '2025-04-11 23:08:36'),
(91, 42, 107, '2025-04-11 23:08:44'),
(92, 42, 105, '2025-04-11 23:08:45'),
(95, 30, 105, '2025-04-12 19:03:02'),
(97, 54, 104, '2025-04-12 19:26:04'),
(99, 54, 105, '2025-04-12 21:12:42');

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
  `seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_account_id` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `id_fk` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
