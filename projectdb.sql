-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 05:09 PM
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
(68, 'abc', 'abc@gmail.com', '$2y$10$b92JesVcYRrEcxi5UXI7me4OOHHnO.MAaXXQXmZzIUh.72P14dcBy', 'business', '/momento/uploads/ProfilePicture/img_681216f9c634b5.47963967.jpg', 'Bahamas', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-26 23:08:31', '2025-04-30 12:42:44'),
(70, 'test', 'farisassaf03@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/img_681641023ee951.25969199.jpg', 'Austria', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '115829613571035348563', '2025-04-29 17:28:02', '2025-05-03 16:14:58'),
(72, 'ttt', 'testt@g.c', '$2y$10$DuuQZOdt7tHbAR2/tIO8He3HQSP5FKiStniAAa5vNMoXE8CSnc/K6', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Austria', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-29 17:52:31', '2025-04-29 18:39:38'),
(73, 'pas', 'pas@g.c', '$2y$10$M8QMpy89jIikJXw32ROhz./dtAVD2keuJFrcYjbxPinM2bRx5ua2e', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-30 13:04:56', '2025-04-30 13:10:38'),
(74, 'TE', 'TE@G.C', '$2y$10$4k98M3r061LE6bUXbXoZZ.TT0Y8u1xW0t9o3WFRLNln9ZgiomegMa', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Azerbaijan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-30 21:07:03', '2025-05-02 11:12:41'),
(75, 'TheNigga', 'user@g.c', '$2y$10$Ct3Xecsm0gpGTpy8uOq3FubI4fW9lh5Z5/UtTMO.0F4QPpwzZL7kG', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Algeria', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-02 19:14:37', '2025-05-03 18:00:31'),
(77, 'gg', 'gg@g.c', '$2y$10$0l5nDVHd58j48lIhNr3yjO3C44kJ6jQoR/rvOCoxjnrqJOpGkq26u', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Andorra', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-03 18:58:15', '2025-05-03 18:58:40'),
(78, 'ccc', 'ccc@g.c', '$2y$10$ByGPHVbFJS4nayZMLIOoVu2b8GYIGV0po3idGe1MITq6VXPdmM4lW', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'South Africa', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-04 02:48:13', '2025-05-04 02:57:42'),
(79, 'cccb', 'cccb@g.c', '$2y$10$yJ4HUcxRHLOOnnajELnFC.KIjwX3ft5MvctHNnBug2m8ssu.Bqsci', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Saudi Arabia', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-04 02:59:49', '2025-05-04 03:00:28'),
(81, 'last', 'last@g.c', '$2y$10$RdcDpqVBzUaZe/zH0Oc4dOV5QdqKIEgpgY4OXBXuTzgUKTpregAPG', 'user', '/momento/Uploads/ProfilePicture/profile_6816dab37ab277.17543306.jpg', 'Brazil', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-04 03:10:35', '2025-05-04 03:11:01'),
(82, 'zeze', 'zeze@g.c', '$2y$10$5HV11Z7/n9EYIYJbeGCUF.JKqrRtbw6Pox.g7Yv1GwKuz/sM.UFci', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'India', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-04 03:12:59', '2025-05-04 03:13:03'),
(83, 'uu', 'uu@g.c', '$2y$10$nBK/A.M.uwlDPHV9zW2hP./NdhAvNWAxjTiMY42C.mccPnHGvtopK', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'India', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-04 03:15:42', '2025-05-04 03:15:45');

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
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `photographer_id` int(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `duration_minutes` int(128) NOT NULL,
  `date` varchar(128) NOT NULL,
  `notes` varchar(512) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `photographer_id`, `full_name`, `email`, `phone_number`, `category`, `location`, `duration_minutes`, `date`, `notes`, `status`, `created_at`) VALUES
(4, 75, 72, 'fdasfdas', 'fdas@g.c', '321321321321', 'wedding', 'outdoor', 240, '2025-05-14 12:00', 'dsfxafd ', 'pending', '2025-05-02 12:59:36'),
(5, 75, 72, 'fdasfdas', 'fdas@g.c', '321321321321', 'portrait', 'outdoor', 30, '2025-05-24 12:00', 'resfdasf', 'pending', '2025-05-02 13:16:26'),
(6, 75, 70, 'lolo', 'lolo@g.c', '4324324324', '', 'outdoor', 30, '2025-05-07 12:30', '432432', 'pending', '2025-05-04 02:29:34'),
(7, 75, 70, '321 3', '213@g.c', '321321321321', '', 'outdoor', 60, '2025-05-11 12:00', '321 31', 'pending', '2025-05-04 02:35:14'),
(8, 75, 70, 'dsadsa', 'fda@g.c', '321321321321', '', 'outdoor', 120, '2025-05-15 15:00', '3213dsffda', 'pending', '2025-05-04 07:27:06'),
(9, 75, 70, 'dsadsa', 'fda@g.c', '321321321321', '', 'outdoor', 60, '2025-05-26 14:00', 'fdasfdasf ', 'pending', '2025-05-04 07:32:45'),
(10, 75, 70, 'dsadsa', 'fda@g.c', '321321321321', '', 'studio', 60, '2025-05-29 12:00', 'fdsfdsg', 'pending', '2025-05-04 07:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_services`
--

CREATE TABLE `appointment_services` (
  `id` int(255) NOT NULL,
  `photographer_id` int(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(512) NOT NULL,
  `price` double NOT NULL,
  `detail_1` varchar(96) NOT NULL,
  `detail_2` varchar(96) NOT NULL,
  `detail_3` varchar(96) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_services`
--

INSERT INTO `appointment_services` (`id`, `photographer_id`, `category`, `description`, `price`, `detail_1`, `detail_2`, `detail_3`, `created_at`) VALUES
(1, 70, 'Portrait session', 'blab balb balb labla blal la lbla blal balbla lbal bal lbal lbal lbal bal blal blab la lbal .', 30, 'abc', 'cdd', 'bbc', '2025-05-02 05:17:42');

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
  `total_rate` float NOT NULL DEFAULT 0,
  `total_reviews` int(255) NOT NULL DEFAULT 0,
  `total_likes` int(255) NOT NULL DEFAULT 0,
  `total_views` int(255) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_profiles`
--

INSERT INTO `business_profiles` (`id`, `business_name`, `gender`, `contact_number`, `social_links`, `bio`, `job`, `total_rate`, `total_reviews`, `total_likes`, `total_views`, `created_at`, `updated_at`) VALUES
(68, 'test', 'male', NULL, NULL, 'gfds gfds gf sddfs', 'test', 0, 0, 0, 0, '2025-04-26 23:08:31', '2025-04-30 12:41:15'),
(70, 'Faris', 'not specifed', NULL, NULL, '', 'bobobob', 3, 1, 2, 6, '2025-04-29 17:28:02', '2025-05-04 12:56:09'),
(72, 'test', 'male', NULL, NULL, '', NULL, 0, 0, 0, 0, '2025-04-29 17:52:31', '2025-05-04 14:16:46'),
(73, '', 'male', NULL, NULL, '', NULL, 0, 0, 0, 0, '2025-04-30 13:04:56', '2025-04-30 13:04:56'),
(74, 'abo ahmed', 'male', NULL, NULL, 'nnnnn', 'test', 0, 0, 0, 0, '2025-04-30 21:07:03', '2025-05-02 11:12:41'),
(79, 'ccccb', 'male', '321321321312', '\"test,test,test\"', 'gfdkognmsdgfdsgfdsg', 'photographer pp', 0, 0, 0, 0, '2025-05-04 02:59:49', '2025-05-04 03:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `image_id` int(255) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `likes` int(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `image_id`, `comment`, `likes`, `created_at`) VALUES
(26, 77, 156, 'test', 1, '2025-05-03 12:01:11'),
(27, 77, 156, 'test', 1, '2025-05-03 12:02:46'),
(28, 77, 156, 'fdsa', 1, '2025-05-03 12:03:45'),
(29, 77, 156, 'fdas d', 1, '2025-05-03 12:32:16'),
(30, 77, 156, 'fdas fdas', 1, '2025-05-03 12:38:50'),
(31, 77, 156, 'f dasfda', 0, '2025-05-03 12:38:55'),
(32, 77, 156, 'TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT', 0, '2025-05-03 12:39:36'),
(33, 77, 156, 'WHY', 1, '2025-05-03 12:40:04'),
(34, 70, 156, 'THIS IS PHOTOGRAPHER COMMENT', 0, '2025-05-03 13:13:49'),
(35, 70, 156, 'fdsa', 1, '2025-05-03 13:16:22'),
(36, 70, 157, 'test', 1, '2025-05-03 13:24:51'),
(37, 70, 156, 'fds a', 0, '2025-05-03 13:44:38'),
(38, 70, 156, 'fdsa fdfda sfd asfd asfda sf', 0, '2025-05-03 13:45:41'),
(39, 70, 155, 'hello cat', 1, '2025-05-03 14:18:29'),
(40, 70, 158, 'fdas', 1, '2025-05-03 19:30:11'),
(41, 74, 158, 'ffgdsa', 1, '2025-05-04 05:55:57'),
(42, 74, 158, 'fdsaf da', 0, '2025-05-04 05:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `user_id`, `comment_id`, `created_at`) VALUES
(8, 77, 27, '2025-05-03 12:32:07'),
(9, 77, 28, '2025-05-03 12:32:08'),
(11, 77, 29, '2025-05-03 12:40:42'),
(12, 77, 30, '2025-05-03 12:40:43'),
(13, 77, 33, '2025-05-03 12:40:45'),
(15, 70, 35, '2025-05-03 13:17:24'),
(17, 70, 36, '2025-05-03 13:25:10'),
(18, 70, 26, '2025-05-03 13:45:44'),
(19, 70, 39, '2025-05-03 14:18:36'),
(20, 74, 40, '2025-05-04 05:56:00'),
(21, 74, 41, '2025-05-04 05:56:02');

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
(16, 68, 1, '2025-04-26 23:08:39'),
(18, 70, 1, '2025-04-29 17:28:02'),
(19, 72, 1, '2025-04-29 17:52:39'),
(20, 73, 1, '2025-04-30 13:05:05'),
(21, 74, 1, '2025-05-01 17:46:58'),
(22, 75, 1, '2025-05-02 19:15:36'),
(23, 77, 1, '2025-05-03 18:58:31'),
(24, 75, 72, '2025-05-03 23:44:38'),
(25, 70, 72, '2025-05-04 00:08:22'),
(26, 74, 70, '2025-05-04 00:47:27'),
(27, 74, 74, '2025-05-04 01:26:00'),
(28, 78, 1, '2025-05-04 02:56:51'),
(29, 79, 1, '2025-05-04 03:01:09'),
(31, 81, 1, '2025-05-04 03:10:43'),
(32, 82, 1, '2025-05-04 03:13:03'),
(33, 83, 1, '2025-05-04 03:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` varchar(18) NOT NULL,
  `end_date` varchar(18) DEFAULT 'present',
  `company` varchar(169) NOT NULL,
  `job` varchar(128) NOT NULL,
  `achievements` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `user_id`, `start_date`, `end_date`, `company`, `job`, `achievements`) VALUES
(1, 68, '2025-04-07', '2025-04-22', ' tesa', 'tesaw', 'fdsa fd safdas fdsa'),
(4, 68, '2025-03-31', 'present', ' fdas', 'fdsa', 'f dsafda'),
(8, 74, '2025-04-28', '2025-04-30', ' hgdfs', 'ghfd', 'hg fd');

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
  `title` varchar(255) NOT NULL,
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

INSERT INTO `images` (`id`, `user_id`, `label`, `title`, `description`, `views`, `likes`, `file_name`, `url`, `created_at`, `updated_at`) VALUES
(155, 70, '[\"cat\",\"wildlife\",\"animal\",\"social\",\"pets\"]', '', 'tetetete', 5, 2, 'img_681629ecd8fee8.62213596.jpg', 'uploads/Images/img_681629ecd8fee8.62213596.jpg', '2025-05-03 14:36:28', '2025-05-04 12:56:09'),
(156, 70, '[\"smart cities\",\"travel\",\"egypt\",\"tourism\",\"events\"]', 'gfds', ' gfdsg fsd', 5, 4, 'img_681638e1885186.62597077.jpg', 'uploads/Images/img_681638e1885186.62597077.jpg', '2025-05-03 15:40:17', '2025-05-04 10:24:54'),
(157, 70, '[\"travel\",\"smart cities\",\"events\",\"tourism\",\"zarqa\"]', '', 'fdas fdas', 4, 3, 'img_6816392d861891.07227816.jpg', 'uploads/Images/img_6816392d861891.07227816.jpg', '2025-05-03 15:41:33', '2025-05-04 12:39:26'),
(158, 70, '[\"cat\",\"animal\",\"wildlife\",\"pets\",\"social\"]', 'Cat', 'NICE CAT', 3, 2, 'img_6816cb37092924.56054176.jpg', 'uploads/Images/img_6816cb37092924.56054176.jpg', '2025-05-04 02:04:39', '2025-05-04 12:55:52');

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
-- Table structure for table `image_view_logs`
--

CREATE TABLE `image_view_logs` (
  `id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `view_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image_view_logs`
--

INSERT INTO `image_view_logs` (`id`, `image_id`, `user_id`, `view_date`) VALUES
(36, 155, 70, '2025-05-04'),
(40, 155, 74, '2025-05-04'),
(35, 156, 70, '2025-05-04'),
(38, 157, 70, '2025-05-04'),
(37, 158, 70, '2025-05-04'),
(39, 158, 74, '2025-05-04');

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
(158, 75, 156, '2025-05-03 18:54:34'),
(170, 77, 156, '2025-05-03 20:07:41'),
(171, 77, 157, '2025-05-03 20:10:05'),
(178, 70, 157, '2025-05-03 20:58:13'),
(189, 70, 155, '2025-05-03 22:01:23'),
(190, 75, 155, '2025-05-03 23:56:04'),
(191, 70, 158, '2025-05-04 02:30:13'),
(192, 78, 156, '2025-05-04 02:59:17'),
(193, 78, 157, '2025-05-04 02:59:28'),
(198, 70, 156, '2025-05-04 10:18:40'),
(199, 74, 158, '2025-05-04 12:55:52');

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
(147, 18, 70, 'll', '2025-05-01 11:14:50', 0, NULL),
(148, 24, 75, 'fdas', '2025-05-03 23:44:38', 0, NULL),
(149, 24, 75, 'gfdsg', '2025-05-04 00:07:02', 0, NULL),
(150, 24, 75, 'gfdsg fsdgf', '2025-05-04 00:07:06', 0, NULL),
(151, 24, 75, 'g fsdgsdgfs', '2025-05-04 00:07:13', 0, NULL),
(152, 24, 75, 'gsdfg', '2025-05-04 00:07:14', 0, NULL),
(153, 25, 70, 'ysfgd', '2025-05-04 00:08:22', 0, NULL),
(154, 25, 70, 'dasfdas', '2025-05-04 00:36:23', 0, NULL),
(155, 25, 70, 'vczx', '2025-05-04 00:39:03', 0, NULL),
(156, 25, 70, 'test', '2025-05-04 00:46:19', 0, NULL),
(157, 25, 70, 'teasd', '2025-05-04 00:46:29', 0, NULL),
(158, 25, 70, 'fads', '2025-05-04 00:46:33', 0, NULL),
(159, 25, 70, 'gfsd', '2025-05-04 00:46:49', 0, NULL),
(160, 26, 74, 'hello fdsmfidasf', '2025-05-04 00:47:27', 1, NULL),
(161, 26, 70, 'yes hello', '2025-05-04 00:47:51', 1, NULL),
(162, 25, 70, 'test', '2025-05-04 00:50:55', 0, NULL),
(163, 26, 70, 'test', '2025-05-04 00:56:47', 1, NULL),
(164, 26, 70, 'what', '2025-05-04 00:57:13', 1, NULL),
(165, 25, 70, 'test', '2025-05-04 00:59:07', 0, NULL),
(166, 25, 70, 'WHAT IS GOING', '2025-05-04 00:59:23', 0, NULL),
(167, 25, 70, 'FDSAFDASFDASFDAS FDSAFDSAFDSAFSDAFDSAFDSFDSAF', '2025-05-04 00:59:37', 0, NULL),
(168, 26, 70, 'fdas', '2025-05-04 01:11:56', 1, NULL),
(169, 26, 70, 'AFTER UPDATE', '2025-05-04 01:12:49', 1, NULL),
(170, 26, 70, 'AFTER DELETE LOAD', '2025-05-04 01:17:26', 1, NULL),
(171, 26, 74, 'test', '2025-05-04 01:20:44', 1, NULL),
(172, 26, 74, 'testtt', '2025-05-04 01:20:57', 1, NULL),
(173, 27, 74, 'test', '2025-05-04 01:26:00', 1, NULL),
(174, 26, 74, 'test', '2025-05-04 01:52:43', 1, NULL),
(175, 27, 74, 'test', '2025-05-04 01:52:50', 1, '2025-05-03'),
(176, 26, 74, 'helloooo', '2025-05-04 01:53:09', 1, NULL),
(177, 26, 74, 'wlaaaak', '2025-05-04 01:54:17', 1, '2025-05-03'),
(178, 26, 74, 'test', '2025-05-04 01:54:36', 1, NULL),
(179, 26, 70, 'DATABASE', '2025-05-04 01:55:05', 1, '2025-05-03'),
(180, 26, 70, 'DA', '2025-05-04 01:55:08', 1, '2025-05-03'),
(181, 26, 70, 'hello', '2025-05-04 01:55:56', 1, '2025-05-03'),
(182, 26, 74, 'test', '2025-05-04 01:57:09', 1, '2025-05-03'),
(183, 27, 74, 'test', '2025-05-04 01:57:15', 1, '2025-05-03'),
(184, 26, 74, 'LAST UPDATE', '2025-05-04 02:01:36', 1, NULL),
(185, 26, 74, 'FARIS', '2025-05-04 02:03:00', 1, NULL),
(186, 26, 70, 'test', '2025-05-04 12:38:27', 1, NULL),
(187, 25, 70, 'test', '2025-05-04 12:38:31', 0, NULL),
(188, 26, 74, 'ya halla', '2025-05-04 12:55:41', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `photographer_view_logs`
--

CREATE TABLE `photographer_view_logs` (
  `id` int(11) NOT NULL,
  `photographer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `view_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photographer_view_logs`
--

INSERT INTO `photographer_view_logs` (`id`, `photographer_id`, `user_id`, `view_date`) VALUES
(2, 68, 70, '2025-05-04'),
(1, 72, 70, '2025-05-04');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `photographer_id` int(255) NOT NULL,
  `rate` double DEFAULT NULL,
  `comment` varchar(2048) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `photographer_id`, `rate`, `comment`, `created_at`) VALUES
(8, 75, 70, 3, 'TEST REVIEW RATE', '2025-05-04 11:48:36');

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
(75, 'nigga', 'male', '', NULL, '2025-05-02 19:14:37', '2025-05-02 23:35:40'),
(77, 'gg', 'male', '', NULL, '2025-05-03 18:58:15', '2025-05-03 18:58:40'),
(78, 'faris', 'male', '', NULL, '2025-05-04 02:48:13', '2025-05-04 02:57:42'),
(81, 'testlast', 'male', '', NULL, '2025-05-04 03:10:35', '2025-05-04 03:10:43'),
(82, 'zeze', 'male', '', NULL, '2025-05-04 03:12:59', '2025-05-04 03:13:03'),
(83, 'uu', 'male', '', NULL, '2025-05-04 03:15:42', '2025-05-04 03:15:45');

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
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD KEY `api_key_user_id_fk` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`),
  ADD KEY `appointment_photographer_id_fk` (`photographer_id`),
  ADD KEY `appointment_user_id_fk` (`user_id`);

--
-- Indexes for table `appointment_services`
--
ALTER TABLE `appointment_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_photographer_id_fk` (`photographer_id`);

--
-- Indexes for table `business_profiles`
--
ALTER TABLE `business_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_id_comment` (`image_id`),
  ADD KEY `user_id_comment` (`user_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`comment_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convs_senderid_fk` (`sender_Id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `experience_photographer_id_fk` (`user_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_photographer_id_fk` (`user_id`);

--
-- Indexes for table `image_downloads`
--
ALTER TABLE `image_downloads`
  ADD KEY `image_id_download_fk` (`image_id`),
  ADD KEY `user_id_download_fk` (`user_id`);

--
-- Indexes for table `image_view_logs`
--
ALTER TABLE `image_view_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `image_id` (`image_id`,`user_id`,`view_date`);

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
-- Indexes for table `photographer_view_logs`
--
ALTER TABLE `photographer_view_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `photographer_id` (`photographer_id`,`user_id`,`view_date`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_reviews` (`user_id`),
  ADD KEY `photographer_id_reviews` (`photographer_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `image_view_logs`
--
ALTER TABLE `image_view_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `photographer_view_logs`
--
ALTER TABLE `photographer_view_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_key_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointment_photographer_id_fk` FOREIGN KEY (`photographer_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointment_services`
--
ALTER TABLE `appointment_services`
  ADD CONSTRAINT `services_photographer_id_fk` FOREIGN KEY (`photographer_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `business_profiles`
--
ALTER TABLE `business_profiles`
  ADD CONSTRAINT `fk_account_b_id` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `image_id_comment` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_comment` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `convs_senderid_fk` FOREIGN KEY (`sender_Id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_photographer_id_fk` FOREIGN KEY (`user_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_photographer_id_fk` FOREIGN KEY (`user_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image_downloads`
--
ALTER TABLE `image_downloads`
  ADD CONSTRAINT `image_id_download_fk` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_download_fk` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image_view_logs`
--
ALTER TABLE `image_view_logs`
  ADD CONSTRAINT `image_id_views_fk` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `photographer_view_logs`
--
ALTER TABLE `photographer_view_logs`
  ADD CONSTRAINT `photographer_id` FOREIGN KEY (`photographer_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `photographer_id_reviews` FOREIGN KEY (`photographer_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_reviews` FOREIGN KEY (`user_id`) REFERENCES `user_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
