-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 02:48 PM
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
  `provider` enum('local','google') NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `account_type`, `picture`, `location`, `lon`, `lat`, `provider`, `provider_id`, `created_at`, `updated_at`) VALUES
(103, 'test', 'farisassaf03@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/google_115829613571035348563.jpg', 'Australia', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '115829613571035348563', '2025-05-13 20:15:20', '2025-05-13 20:15:20'),
(104, 'testafter', 'testafter@g.c', '$2y$10$nxJCu/JjGKAznb11HCTT7eLEIDnSRnBDrX9Iqf7zvbMImeO4G9kAW', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Burundi', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-13 23:27:27', '2025-05-13 23:39:43'),
(105, 'testu', 'testu@g.c', '$2y$10$RImYjHUB9PaNDPRex8IvUu3MM568niZFinJxOc4w5ILJofTXR1Ai2', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Cyprus', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-13 23:40:44', '2025-05-13 23:41:23'),
(106, 'oshiro', 'farismusleh2032003@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/google_113031318110845926527.jpg', 'Azerbaijan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '113031318110845926527', '2025-05-14 07:53:42', '2025-05-14 07:53:42'),
(107, 'app', 'app@g.c', '$2y$10$a8uU7fz81TR9IITspR4dxOyjJQexWReIM7ZR9PxbFHMudDA/Tg286', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Bahamas', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-14 11:02:49', '2025-05-14 11:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(255) NOT NULL,
  `api_key` varchar(72) NOT NULL,
  `user_id` int(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `api_key`, `user_id`, `is_active`, `created_at`) VALUES
(1, '335aa9ec70256599bab3a1ed3c712c044f1bce4e8de8703f77256d958ee53ea1', 105, 1, '2025-05-14 03:22:35');

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
  `payment_method` enum('cash','paypal') NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `photographer_id`, `full_name`, `email`, `phone_number`, `category`, `location`, `duration_minutes`, `date`, `notes`, `status`, `payment_method`, `seen`, `created_at`) VALUES
(11, 107, 103, 'rew', 'fdas@g.c', '321', 'Event', 'studio', 60, '2025-05-12 12:00', 'fdsa', 'confirmed', 'cash', 1, '2025-05-14 14:04:01');

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
(9, 103, 'Event', 'dfsa', 321, '321', '321', '321', '2025-05-14 14:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `business_profiles`
--

CREATE TABLE `business_profiles` (
  `id` int(11) NOT NULL,
  `business_name` varchar(256) NOT NULL,
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

INSERT INTO `business_profiles` (`id`, `business_name`, `contact_number`, `social_links`, `bio`, `job`, `total_rate`, `total_reviews`, `total_likes`, `total_views`, `created_at`, `updated_at`) VALUES
(103, 'Faris', NULL, NULL, '', NULL, 0, 0, 2, 14, '2025-05-13 20:15:20', '2025-05-14 12:46:36'),
(104, 'ft7e', '01231231231', NULL, '32132', 'fdsa', 0, 0, 0, 0, '2025-05-13 23:27:27', '2025-05-13 23:39:43'),
(106, '̇Oshiro', NULL, NULL, '', NULL, 0, 0, 6, 17, '2025-05-14 07:53:42', '2025-05-14 12:46:37');

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
(77, 106, 280, 'tesdt', 1, '2025-05-14 11:22:35'),
(78, 106, 280, '****', 0, '2025-05-14 11:22:42');

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
(34, 106, 77, '2025-05-14 11:22:38');

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
(47, 103, 1, '2025-05-13 20:15:20'),
(48, 104, 1, '2025-05-13 23:39:43'),
(49, 105, 1, '2025-05-13 23:41:23'),
(50, 106, 1, '2025-05-14 07:53:42'),
(51, 107, 1, '2025-05-14 11:02:58');

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

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(128) NOT NULL,
  `follower_id` int(128) NOT NULL,
  `followee_id` int(128) NOT NULL,
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
  `is_sensitive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `label`, `title`, `description`, `views`, `likes`, `file_name`, `url`, `is_sensitive`, `created_at`, `updated_at`) VALUES
(276, 103, 'null', '', '', 4, 2, 'img_6823b3f011bef8.36846466.jpg', 'uploads/Images/img_6823b3f011bef8.36846466.jpg', 0, '2025-05-13 21:04:48', '2025-05-14 09:26:00'),
(278, 106, '[\"gaza\"]', '', 'a man is lying on the ground with his head down', 2, 0, 'img_6824504ca494e1.29799288.jpg', 'uploads/Images/img_6824504ca494e1.29799288.jpg', 0, '2025-05-14 08:11:56', '2025-05-14 12:08:46'),
(279, 106, '[\"tank\"]', '', 'a small toy tank with wheels on it', 1, 0, 'img_682451e639e048.57902453.jpg', 'uploads/Images/img_682451e639e048.57902453.jpg', 0, '2025-05-14 08:18:46', '2025-05-14 09:21:55'),
(280, 106, '[\"dog\"]', '', 'three dogs are sitting on a wooden bench', 1, 1, 'img_682452bd4d3187.19261059.jpg', 'uploads/Images/img_682452bd4d3187.19261059.jpg', 0, '2025-05-14 08:22:21', '2025-05-14 08:22:28'),
(281, 106, '[\"blood\"]', '', 'blood splashing on a white background', 1, 0, 'img_68245330dc90b1.50680719.jpg', 'uploads/Images/img_68245330dc90b1.50680719.jpg', 1, '2025-05-14 08:24:16', '2025-05-14 08:26:20'),
(282, 106, '[\"tank\"]', '', 'a small toy tank with wheels on it', 2, 2, 'img_682453e838faf3.76509861.jpg', 'uploads/Images/img_682453e838faf3.76509861.jpg', 0, '2025-05-14 08:27:20', '2025-05-14 12:46:37'),
(283, 106, '[\"tank\"]', 'ttt', 'a black and white photo of a tank', 2, 2, 'img_6824540fe0a8b1.00003819.jpg', 'uploads/Images/img_6824540fe0a8b1.00003819.jpg', 0, '2025-05-14 08:27:59', '2025-05-14 09:26:19'),
(284, 106, '[\"dog\"]', '', 'three dogs are sitting on a wooden bench', 0, 0, 'img_682454273d6a85.53477694.jpg', 'uploads/Images/img_682454273d6a85.53477694.jpg', 0, '2025-05-14 08:28:23', '2025-05-14 08:28:23'),
(285, 106, '[\"air\",\"group\",\"balloons\",\"city\"]', '', 'a group of hot air balloons flying over a city', 1, 1, 'img_682454357db650.89577711.jpg', 'uploads/Images/img_682454357db650.89577711.jpg', 0, '2025-05-14 08:28:37', '2025-05-14 08:49:41'),
(286, 106, '[\"cat\"]', '', 'a small kitten sitting on a tiled floor', 1, 0, 'img_6824544a1902e3.59895572.jpg', 'uploads/Images/img_6824544a1902e3.59895572.jpg', 0, '2025-05-14 08:28:58', '2025-05-14 08:29:00'),
(287, 106, '[\"air\",\"group\",\"balloons\",\"city\"]', '', 'a group of hot air balloons flying over a city', 0, 0, 'img_6824545fb2d396.12612628.jpg', 'uploads/Images/img_6824545fb2d396.12612628.jpg', 0, '2025-05-14 08:29:19', '2025-05-14 08:29:19'),
(288, 106, '[\"lion\"]', '', 'a black and white photo of a lion', 0, 0, 'img_68245471e71fc3.50934835.jpg', 'uploads/Images/img_68245471e71fc3.50934835.jpg', 0, '2025-05-14 08:29:37', '2025-05-14 08:29:37'),
(289, 106, '[\"food\",\"health\"]', '', 'a bowl of salad with chicken, vegetables and eggs', 1, 0, 'img_6824547e1ed733.64307749.jpg', 'uploads/Images/img_6824547e1ed733.64307749.jpg', 0, '2025-05-14 08:29:50', '2025-05-14 10:06:47'),
(290, 106, '[\"tourism\"]', '', 'rafting on the river', 0, 0, 'img_6824548c1c31e9.09080336.jpg', 'uploads/Images/img_6824548c1c31e9.09080336.jpg', 0, '2025-05-14 08:30:04', '2025-05-14 08:30:04'),
(291, 106, '[\"tank\"]', '', 'a small toy tank with wheels on it', 1, 0, 'img_6824549735cef3.40295308.jpg', 'uploads/Images/img_6824549735cef3.40295308.jpg', 0, '2025-05-14 08:30:15', '2025-05-14 08:30:19'),
(292, 106, '[\"cat\"]', '', 'a small kitten sitting on a tiled floor', 1, 0, 'img_682454b2492800.21012747.jpg', 'uploads/Images/img_682454b2492800.21012747.jpg', 0, '2025-05-14 08:30:42', '2025-05-14 09:03:07'),
(293, 106, '[\"gaza\"]', '', 'a man walks through the rubble of a building in the aftermath', 1, 0, 'img_682454c8c05318.06544176.jpg', 'uploads/Images/img_682454c8c05318.06544176.jpg', 0, '2025-05-14 08:31:04', '2025-05-14 08:31:07'),
(294, 106, '[\"palestine\"]', '', 'a young boy waves a palestinian flag', 0, 0, 'img_682454d80f7592.44346089.jpg', 'uploads/Images/img_682454d80f7592.44346089.jpg', 0, '2025-05-14 08:31:20', '2025-05-14 08:31:20'),
(295, 106, '[\"palestine\"]', '', 'a young boy waves a palestinian flag', 0, 0, 'img_682454e0d29d53.85257623.jpg', 'uploads/Images/img_682454e0d29d53.85257623.jpg', 0, '2025-05-14 08:31:28', '2025-05-14 08:31:28'),
(296, 106, '[\"gaza\"]', '', 'a man is lying on the ground with his head down', 0, 0, 'img_682454eb2c2db0.30638281.jpg', 'uploads/Images/img_682454eb2c2db0.30638281.jpg', 0, '2025-05-14 08:31:39', '2025-05-14 08:31:39'),
(297, 106, '[\"cat\"]', '', 'a small kitten sitting on a tiled floor', 2, 0, 'img_682454f5363480.55238794.jpg', 'uploads/Images/img_682454f5363480.55238794.jpg', 0, '2025-05-14 08:31:49', '2025-05-14 09:35:08'),
(298, 103, '[\"desert\"]', '', 'a person walking across a desert', 1, 0, 'img_68246e142e8c92.26551909.jpg', 'uploads/Images/img_68246e142e8c92.26551909.jpg', 0, '2025-05-14 10:19:00', '2025-05-14 12:46:36'),
(299, 103, '[\"tank\"]', '', 'a small toy tank with wheels on it', 1, 0, 'img_68246e4fbec9a5.16884591.jpg', 'uploads/Images/img_68246e4fbec9a5.16884591.jpg', 0, '2025-05-14 10:19:59', '2025-05-14 10:21:16'),
(305, 103, '[\"gore\",\"war\"]', '', 'a man is buried in the mud', 1, 0, 'img_682470ab196df5.72128218.jpg', 'uploads/Images/img_682470ab196df5.72128218.jpg', 0, '2025-05-14 10:30:03', '2025-05-14 10:30:05'),
(306, 103, '[\"gore\",\"war\"]', '', 'a man is buried in the mud', 1, 0, 'img_682470d0b37ee1.77060263.jpg', 'uploads/Images/img_682470d0b37ee1.77060263.jpg', 0, '2025-05-14 10:30:40', '2025-05-14 10:30:43'),
(307, 103, '[\"gore\",\"war\"]', '', 'a man is buried in the mud', 1, 0, 'img_6824714fb91725.09721200.jpg', 'uploads/Images/img_6824714fb91725.09721200.jpg', 0, '2025-05-14 10:32:47', '2025-05-14 10:32:49'),
(308, 103, '[\"gore\",\"war\"]', '', 'a man is buried in the mud', 1, 0, 'img_68247235d42629.29863720.jpg', 'uploads/Images/img_68247235d42629.29863720.jpg', 0, '2025-05-14 10:36:37', '2025-05-14 10:36:43'),
(309, 103, '[\"gaza\"]', '', 'a view of the destroyed city of mogad, iraq, on april 29, 2014', 0, 0, 'img_6824729b5d3585.17388516.jpg', 'uploads/Images/img_6824729b5d3585.17388516.jpg', 0, '2025-05-14 10:38:19', '2025-05-14 10:38:19'),
(310, 103, '[\"gore\",\"war\"]', '', 'a man is buried in the mud', 0, 0, 'img_682472aeb6c214.81911279.jpg', 'uploads/Images/img_682472aeb6c214.81911279.jpg', 0, '2025-05-14 10:38:38', '2025-05-14 10:38:38'),
(311, 103, '[\"gore\",\"war\"]', '', 'a man is buried in the mud', 0, 0, 'img_682473093c9ae3.76606452.jpg', 'uploads/Images/img_682473093c9ae3.76606452.jpg', 0, '2025-05-14 10:40:09', '2025-05-14 10:40:09');

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
(192, 276, 103, '2025-05-13'),
(208, 276, 103, '2025-05-14'),
(193, 276, 105, '2025-05-14'),
(194, 276, 106, '2025-05-14'),
(222, 278, 103, '2025-05-14'),
(197, 278, 106, '2025-05-14'),
(195, 279, 106, '2025-05-14'),
(196, 280, 106, '2025-05-14'),
(198, 281, 106, '2025-05-14'),
(207, 282, 103, '2025-05-14'),
(199, 282, 106, '2025-05-14'),
(209, 283, 103, '2025-05-14'),
(200, 283, 106, '2025-05-14'),
(205, 285, 106, '2025-05-14'),
(201, 286, 106, '2025-05-14'),
(211, 289, 103, '2025-05-14'),
(202, 291, 106, '2025-05-14'),
(206, 292, 106, '2025-05-14'),
(203, 293, 106, '2025-05-14'),
(210, 297, 103, '2025-05-14'),
(204, 297, 106, '2025-05-14'),
(212, 298, 103, '2025-05-14'),
(213, 299, 103, '2025-05-14'),
(218, 305, 103, '2025-05-14'),
(219, 306, 103, '2025-05-14'),
(220, 307, 103, '2025-05-14'),
(221, 308, 103, '2025-05-14');

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
(222, 105, 276, '2025-05-13 23:41:36'),
(224, 106, 280, '2025-05-14 08:22:28'),
(226, 106, 283, '2025-05-14 08:28:09'),
(227, 106, 285, '2025-05-14 08:49:41'),
(228, 106, 282, '2025-05-14 09:02:37'),
(240, 103, 276, '2025-05-14 09:26:00'),
(241, 103, 283, '2025-05-14 09:26:19'),
(245, 103, 282, '2025-05-14 12:46:37');

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

-- --------------------------------------------------------

--
-- Table structure for table `paypal_payments`
--

CREATE TABLE `paypal_payments` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photographer_category_scores`
--

CREATE TABLE `photographer_category_scores` (
  `photographer_id` int(11) NOT NULL,
  `category` varchar(512) NOT NULL,
  `score` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photographer_category_scores`
--

INSERT INTO `photographer_category_scores` (`photographer_id`, `category`, `score`) VALUES
(103, 'wars', 2);

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
(15, 103, 106, '2025-05-14'),
(17, 103, 107, '2025-05-14'),
(14, 104, 105, '2025-05-14'),
(16, 106, 103, '2025-05-14');

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

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(105, 'ffffffffffffffffffffffffffffffffffff', '2025-05-13 23:40:44', '2025-05-13 23:41:23'),
(107, 'app', '2025-05-14 11:02:49', '2025-05-14 11:02:58');

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
  ADD PRIMARY KEY (`id`),
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
  ADD UNIQUE KEY `user_id` (`user_id`,`comment_id`),
  ADD KEY `comment_id_comments_likes_fk` (`comment_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `followers_follower_id_fk` (`follower_id`),
  ADD KEY `followers_followe_id_fk` (`followee_id`);

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
-- Indexes for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `photographer_category_scores`
--
ALTER TABLE `photographer_category_scores`
  ADD PRIMARY KEY (`photographer_id`,`category`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `image_view_logs`
--
ALTER TABLE `image_view_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photographer_view_logs`
--
ALTER TABLE `photographer_view_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_id_comments_likes_fk` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_comments_likes_fk` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_followe_id_fk` FOREIGN KEY (`followee_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `followers_follower_id_fk` FOREIGN KEY (`follower_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD CONSTRAINT `paypal_payments_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photographer_category_scores`
--
ALTER TABLE `photographer_category_scores`
  ADD CONSTRAINT `photographer_category_scores_ibfk_1` FOREIGN KEY (`photographer_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_account_id` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
