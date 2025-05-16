-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 07:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(113, 'waleed_alnablisieha', 'waleedalnablisieha2017@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/google_118369701700260045816.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '118369701700260045816', '2025-05-16 12:49:32', '2025-05-16 12:49:32'),
(114, 'waleed', 'waleed.alnablisieha03@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/google_103444607268620559817.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '103444607268620559817', '2025-05-16 13:40:41', '2025-05-16 13:40:41'),
(115, 'waleed2024', 'waleedalnablisieha2024@gmail.com', '$2y$10$VCNqIUCHHySh5Qu29MSga.dqliYB9cxzEg9ASSplH9c8b1m1av2dO', 'business', '/momento/uploads/ProfilePicture/img_6827414da86aa7.86368289.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 13:43:59', '2025-05-16 13:44:45'),
(116, 'waleed2025', 'waleedalnablisieha2025@gmail.com', '$2y$10$xL5QkRnFbEnCrw.DN6j65.4u52wiGPy40JpIfZTg4sWIxGFpqXxDu', 'business', '/momento/uploads/ProfilePicture/img_68274345d72b46.56348570.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 13:48:10', '2025-05-16 13:53:09'),
(117, 'waleed2026', 'waleedalnablisieha2026@gmail.com', '$2y$10$PTNGTeY9SCBsg6GbAZ5DgOZ7be2ekmU9iXbp0.s7M5LtamMlRp7MW', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 13:53:39', '2025-05-16 13:54:11'),
(118, 'waleed2022', 'waleedalnablisieha2022@gmail.com', '$2y$10$.SJwPlRTViRxKYNEpTv6nOuIpeqHK2zKM5CQ6mp9hf6z92pLoOiXW', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 13:54:41', '2025-05-16 13:55:23'),
(119, 'waleed2021', 'waleedalnablisieha2021@gmail.com', '$2y$10$juxhj2/u8BpnapTjSQ1dJO6dioNwrBNj3KnZ2goGfjESN2j1xkNUi', 'business', '/momento/uploads/ProfilePicture/img_68274525438831.20148780.jpg', 'Turkey', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 13:56:14', '2025-05-16 14:01:10'),
(120, 'waleed-graduation', 'waleedalnablisieha2020@gmail.com', '$2y$10$oTsNS2MjI/V9AbKhPWMp3.pWAEY.F3YhVGk4LHQzgkkX9utzDgjRO', 'business', '/momento/uploads/ProfilePicture/img_6827477c763154.02274146.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 14:01:46', '2025-05-16 14:11:08'),
(121, 'waleed2019', 'waleedalnablisieha2019@gmail.com', '$2y$10$bB0ncgUnVb8HyTifyJC2ju2CSZOcmBr1gu3jSaeevEoZSltZpYOLq', 'business', '/momento/uploads/ProfilePicture/img_68274f9962c2f2.98052675.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 14:15:22', '2025-05-16 14:45:45'),
(122, 'waleed2018', 'waleedalnablisieha2018@gmail.com', '$2y$10$ESUZLWvWSWrEt3EiwpYIf.bweATPEczoHkjUPnSlu43h41GVmNKCG', 'business', '/momento/uploads/ProfilePicture/img_68275481e3ebc9.46824185.jpg', 'Jordan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 14:49:52', '2025-05-16 15:06:42'),
(123, 'waleed-war', 'waleedalnablisieha2016@gmail.com', '$2y$10$mUXfoSimSos9IHH7Du67d..WsoaUhdZm1GOco20DBGQS8VoDS65qa', 'business', '/momento/uploads/ProfilePicture/img_68275b3807ae89.70879146.jpeg', 'Palestine', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-05-16 15:10:24', '2025-05-16 15:35:20');

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
(113, 'Waleed Alnablisieha', NULL, '{\"facebook\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"twitter\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"linked-in\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"instagram\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\"}', 'Front End Developer . using a latest technologies to develop a dynamic and responsive web pages .\r\n', 'web developer', 0, 0, 9, 2, '2025-05-16 12:49:32', '2025-05-16 17:20:14'),
(114, 'waleed alnablisieha', NULL, NULL, '', NULL, 0, 0, 8, 7, '2025-05-16 13:40:41', '2025-05-16 17:20:27'),
(115, 'waleed2024', '0798708036', NULL, 'wedding photographer', 'wedding photographer', 0, 0, 4, 2, '2025-05-16 13:43:59', '2025-05-16 17:19:34'),
(116, 'waleed2025', '0798708036', NULL, 'cars photographer', 'cars photographer', 0, 0, 9, 1, '2025-05-16 13:48:10', '2025-05-16 17:20:07'),
(119, 'waleed-Istanbul', '0798708036', NULL, 'Istanbul photographer', '', 0, 0, 10, 2, '2025-05-16 13:56:15', '2025-05-16 17:20:33'),
(120, 'waleed-graduation', '0798708036', '{\"facebook\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"twitter\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"linked-in\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"instagram\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\"}', 'graduation photographer', 'graduation photographer', 0, 0, 7, 5, '2025-05-16 14:01:46', '2025-05-16 17:19:55'),
(121, 'waleed-Nature', '0798708036', '{\"facebook\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"twitter\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"linked-in\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"instagram\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\"}', 'Nature photographer', 'Nature photographer', 0, 0, 7, 1, '2025-05-16 14:15:22', '2025-05-16 17:20:39'),
(122, 'waleed-Art', '0798708036', NULL, 'Art photographer', 'Art photographer', 0, 0, 6, 3, '2025-05-16 14:49:52', '2025-05-16 17:20:09'),
(123, 'waleed-war', '0798708036', '{\"facebook\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"twitter\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"linked-in\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\",\"instagram\":\"https:\\/\\/www.facebook.com\\/AbuKhaleed2003\\/\"}', 'war photographer', 'war photographer', 0, 0, 5, 8, '2025-05-16 15:10:24', '2025-05-16 17:20:25');

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
(86, 122, 337, 'galata kulesi . ne kadar güzelsin be ❤', 0, '2025-05-16 18:09:30');

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
(57, 113, 1, '2025-05-16 12:49:32'),
(58, 114, 1, '2025-05-16 13:40:42'),
(59, 115, 1, '2025-05-16 13:44:29'),
(60, 116, 1, '2025-05-16 13:48:38'),
(61, 117, 1, '2025-05-16 13:54:11'),
(62, 118, 1, '2025-05-16 13:55:24'),
(63, 119, 1, '2025-05-16 13:57:22'),
(64, 120, 1, '2025-05-16 14:02:21'),
(65, 121, 1, '2025-05-16 14:21:38'),
(66, 122, 1, '2025-05-16 14:50:30'),
(67, 123, 1, '2025-05-16 15:10:57');

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
(17, 113, '2024-10-15', 'present', 'Sky Cloud Technologies', 'Developer', ''),
(18, 115, '2025-05-16', 'present', 'wedding planner', 'wedding photographers', ''),
(19, 116, '2025-04-22', 'present', 'cars photorapher', 'cars developer', ''),
(20, 120, '2025-05-12', 'present', 'graduation photographer', 'graduation photographer', ''),
(21, 121, '2025-05-04', 'present', 'Nature photographers', 'Nature photographers', ''),
(22, 122, '2025-05-04', 'present', 'Art photographer', 'Art photographer', ''),
(23, 123, '2025-05-05', 'present', 'war ', 'war photographers', '');

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
(314, 113, '[\"tree\",\"car\",\"flowers\"]', '', 'a car parked in front of a tree with pink flowers', 1, 1, 'img_68273fc09fe6f1.37885879.jpg', 'uploads/Images/img_68273fc09fe6f1.37885879.jpg', 0, '2025-05-16 13:38:09', '2025-05-16 17:18:37'),
(315, 113, '[\"tower\"]', '', 'the eiff tower', 0, 1, 'img_68273fe85b40e9.48068564.jpg', 'uploads/Images/img_68273fe85b40e9.48068564.jpg', 0, '2025-05-16 13:38:48', '2025-05-16 17:19:25'),
(316, 113, '[\"smart cities\"]', '', 'the bridge is tall', 0, 2, 'img_68274002d9e7e7.96611732.jpg', 'uploads/Images/img_68274002d9e7e7.96611732.jpg', 0, '2025-05-16 13:39:14', '2025-05-16 17:19:03'),
(317, 113, '[\"architecture\"]', '', 'a large red building', 0, 2, 'img_682740268b5577.48121411.jpg', 'uploads/Images/img_682740268b5577.48121411.jpg', 0, '2025-05-16 13:39:50', '2025-05-16 17:20:14'),
(318, 114, '[\"architecture\"]', '', 'the building in which the hotel is located', 1, 1, 'img_68274097b5aad4.45377747.jpg', 'uploads/Images/img_68274097b5aad4.45377747.jpg', 0, '2025-05-16 13:41:43', '2025-05-16 17:18:38'),
(319, 114, '[\"corner\",\"hotel\"]', '', 'the hotel at the corner', 1, 2, 'img_682740b72affd6.93274183.jpg', 'uploads/Images/img_682740b72affd6.93274183.jpg', 0, '2025-05-16 13:42:15', '2025-05-16 17:20:26'),
(321, 114, '[\"tourism\"]', '', 'the ri hotel in new york', 1, 1, 'img_682740dc860e66.46940171.jpg', 'uploads/Images/img_682740dc860e66.46940171.jpg', 0, '2025-05-16 13:42:52', '2025-05-16 17:18:41'),
(322, 114, '[\"city\",\"view\",\"room\"]', '', 'a room with a view of the city', 1, 3, 'img_682740ec0160c3.82487646.jpg', 'uploads/Images/img_682740ec0160c3.82487646.jpg', 0, '2025-05-16 13:43:08', '2025-05-16 15:07:24'),
(323, 115, '[\"social\"]', '', 'a bride and groom pose for a photo', 0, 1, 'img_68274167edb6f5.58383646.jpg', 'uploads/Images/img_68274167edb6f5.58383646.jpg', 0, '2025-05-16 13:45:11', '2025-05-16 17:19:32'),
(324, 115, '[\"wedding\"]', '', 'a woman in a wedding dress with a veil', 0, 1, 'img_682741792b0313.49672348.jpg', 'uploads/Images/img_682741792b0313.49672348.jpg', 0, '2025-05-16 13:45:29', '2025-05-16 15:07:34'),
(325, 115, '[\"wedding\"]', '', 'a bride and groom walking through a wedding ceremony', 1, 1, 'img_68274190854c11.36486220.jpg', 'uploads/Images/img_68274190854c11.36486220.jpg', 0, '2025-05-16 13:45:52', '2025-05-16 17:18:43'),
(326, 115, '[\"wedding\"]', '', 'a couple standing in the water at sunset', 0, 1, 'img_682741a47e5235.40824846.jpg', 'uploads/Images/img_682741a47e5235.40824846.jpg', 0, '2025-05-16 13:46:12', '2025-05-16 17:19:34'),
(327, 115, '[\"social\"]', '', 'a bride and groom share a moment together', 1, 0, 'img_682741b6e64058.21297577.jpg', 'uploads/Images/img_682741b6e64058.21297577.jpg', 0, '2025-05-16 13:46:30', '2025-05-16 14:23:46'),
(328, 116, '[\"cars\"]', '', 'the dodge challenger gtx is driving on a highway', 0, 2, 'img_6827424bdebb86.75520953.jpg', 'uploads/Images/img_6827424bdebb86.75520953.jpg', 0, '2025-05-16 13:48:59', '2025-05-16 17:20:07'),
(329, 116, '[\"cars\"]', '', 'a red ferrari parked in a parking lot', 0, 2, 'img_68274257c30c16.77000977.jpg', 'uploads/Images/img_68274257c30c16.77000977.jpg', 0, '2025-05-16 13:49:11', '2025-05-16 17:20:05'),
(330, 116, '[\"cars\",\"electric vehicles\"]', '', 'a black sports car', 0, 1, 'img_6827426862bce8.58263104.jpg', 'uploads/Images/img_6827426862bce8.58263104.jpg', 0, '2025-05-16 13:49:28', '2025-05-16 14:48:41'),
(331, 116, '[\"cars\"]', '', 'a white race car driving down a wet road', 0, 1, 'img_682742df39c2e4.07886041.jpg', 'uploads/Images/img_682742df39c2e4.07886041.jpg', 0, '2025-05-16 13:51:27', '2025-05-16 17:18:34'),
(332, 116, '[\"glcr\",\"suv\",\"mercedes\",\"building\"]', '', 'the mercedes glcr suv is parked outside a building', 1, 1, 'img_682742eb16c358.57774693.jpg', 'uploads/Images/img_682742eb16c358.57774693.jpg', 0, '2025-05-16 13:51:39', '2025-05-16 17:20:04'),
(333, 116, '[\"electric vehicles\"]', '', 'the mercedes gle suv parked on a wooden platform', 0, 2, 'img_682742f6d8a209.02578666.jpg', 'uploads/Images/img_682742f6d8a209.02578666.jpg', 0, '2025-05-16 13:51:50', '2025-05-16 17:18:45'),
(334, 119, '[\"smart cities\",\"architecture\"]', '', 'a bridge over the water', 0, 2, 'img_6827445a3f2f83.46303821.jpg', 'uploads/Images/img_6827445a3f2f83.46303821.jpg', 0, '2025-05-16 13:57:46', '2025-05-16 17:20:33'),
(335, 119, '[\"rock\",\"person\",\"water\"]', '', 'a person sitting on a rock in the water', 0, 2, 'img_68274469a2a880.42996031.jpg', 'uploads/Images/img_68274469a2a880.42996031.jpg', 0, '2025-05-16 13:58:01', '2025-05-16 17:19:13'),
(336, 119, '[\"smart cities\"]', '', 'a concrete wall', 1, 1, 'img_68274479be58d1.01731990.jpg', 'uploads/Images/img_68274479be58d1.01731990.jpg', 0, '2025-05-16 13:58:17', '2025-05-16 17:20:01'),
(337, 119, '[\"tower\"]', '', 'a tall tower with a clock on top of it', 1, 1, 'img_6827448f48de10.21205314.jpg', 'uploads/Images/img_6827448f48de10.21205314.jpg', 0, '2025-05-16 13:58:39', '2025-05-16 15:08:02'),
(338, 119, '[\"smart cities\"]', '', 'the city of istanbul', 0, 1, 'img_682744e223ba71.55052528.jpg', 'uploads/Images/img_682744e223ba71.55052528.jpg', 0, '2025-05-16 14:00:02', '2025-05-16 17:19:39'),
(339, 119, '[\"smart cities\"]', '', 'the bridge is red', 0, 1, 'img_682744f3899405.32727408.jpg', 'uploads/Images/img_682744f3899405.32727408.jpg', 0, '2025-05-16 14:00:19', '2025-05-16 17:19:42'),
(340, 119, '[\"smart cities\"]', '', 'a large body of water', 0, 2, 'img_68274505ee8a43.07755624.jpg', 'uploads/Images/img_68274505ee8a43.07755624.jpg', 0, '2025-05-16 14:00:37', '2025-05-16 17:20:31'),
(341, 120, '[\"graduation\"]', '', 'a woman in a graduation gown holding a sign', 1, 2, 'img_68274581135257.53234595.jpg', 'uploads/Images/img_68274581135257.53234595.jpg', 0, '2025-05-16 14:02:41', '2025-05-16 17:19:06'),
(342, 120, '[\"graduation\"]', '', 'a person holding a diploma and a red ribbon', 2, 1, 'img_6827459c35f125.31744819.jpg', 'uploads/Images/img_6827459c35f125.31744819.jpg', 0, '2025-05-16 14:03:08', '2025-05-16 17:19:17'),
(343, 120, '[\"graduation\"]', '', 'a group of graduates', 1, 0, 'img_682745ab38c600.60530053.jpg', 'uploads/Images/img_682745ab38c600.60530053.jpg', 0, '2025-05-16 14:03:23', '2025-05-16 14:03:41'),
(345, 120, '[\"graduation\"]', '', 'a woman in a graduation gown walking down a path', 0, 1, 'img_682746ced3cd96.96740898.jpg', 'uploads/Images/img_682746ced3cd96.96740898.jpg', 0, '2025-05-16 14:08:14', '2025-05-16 17:19:44'),
(346, 120, '[\"graduation\"]', '', 'a group of people holding up their hats', 0, 1, 'img_6827470cc3e009.65492428.jpg', 'uploads/Images/img_6827470cc3e009.65492428.jpg', 0, '2025-05-16 14:09:16', '2025-05-16 17:19:46'),
(347, 120, '[\"graduation\"]', '', 'a person holding a diploma in their hand', 0, 1, 'img_68274725846e82.16332338.jpg', 'uploads/Images/img_68274725846e82.16332338.jpg', 0, '2025-05-16 14:09:41', '2025-05-16 17:19:52'),
(348, 120, '[\"graduation\"]', '', 'a group of people standing on a hill', 0, 1, 'img_68274747777280.55250051.jpg', 'uploads/Images/img_68274747777280.55250051.jpg', 0, '2025-05-16 14:10:15', '2025-05-16 17:19:55'),
(349, 114, '[\"hotel\",\"night\"]', '', 'the hotel at night', 1, 1, 'img_68274c5634b540.30793938.jpg', 'uploads/Images/img_68274c5634b540.30793938.jpg', 0, '2025-05-16 14:31:50', '2025-05-16 17:20:16'),
(351, 121, '[\"nature\",\"nature\"]', '', 'a pathway with flowers and trees in the background', 0, 1, 'img_68274d9b026bd7.14306353.jpg', 'uploads/Images/img_68274d9b026bd7.14306353.jpg', 0, '2025-05-16 14:37:15', '2025-05-16 17:19:59'),
(352, 121, '[\"nature\",\"nature\"]', '', 'a pond with purple flowers and a bridge', 0, 2, 'img_68274dab6b79e0.00894266.jpg', 'uploads/Images/img_68274dab6b79e0.00894266.jpg', 0, '2025-05-16 14:37:31', '2025-05-16 17:18:48'),
(353, 121, '[\"bunch\",\"leaves\",\"ground\"]', '', 'a bunch of leaves on the ground', 0, 0, 'img_68274eeed9a4a8.93901644.jpg', 'uploads/Images/img_68274eeed9a4a8.93901644.jpg', 0, '2025-05-16 14:42:54', '2025-05-16 14:42:54'),
(354, 121, '[\"beach\"]', '', 'palm trees on the beach in the caribbean islands', 1, 2, 'img_68274efd849927.19614160.jpg', 'uploads/Images/img_68274efd849927.19614160.jpg', 0, '2025-05-16 14:43:09', '2025-05-16 17:18:52'),
(355, 121, '[\"nature\",\"nature\"]', '', 'a forest filled with lots of red leaves', 0, 2, 'img_68274f0fd86130.75798264.jpg', 'uploads/Images/img_68274f0fd86130.75798264.jpg', 0, '2025-05-16 14:43:27', '2025-05-16 17:20:39'),
(356, 121, '[\"islands\",\"seychelles\",\"view\",\"polyand\",\"beach\",\"vegetation\"]', '', 'aerial view of a beach and tropical vegetation in the seychelles islands, french polyand', 0, 0, 'img_68274f273467c8.76212731.jpg', 'uploads/Images/img_68274f273467c8.76212731.jpg', 0, '2025-05-16 14:43:51', '2025-05-16 14:43:51'),
(357, 121, '[\"field\",\"sky\",\"dirt\",\"road\"]', '', 'a dirt road in a field with a blue sky', 0, 0, 'img_68274f3f3f2526.07790249.jpg', 'uploads/Images/img_68274f3f3f2526.07790249.jpg', 0, '2025-05-16 14:44:15', '2025-05-16 14:44:15'),
(358, 121, '[\"ukraine\"]', '', 'a small cabin sits on the edge of a lake', 0, 0, 'img_68274f5ab24b94.06219059.jpg', 'uploads/Images/img_68274f5ab24b94.06219059.jpg', 0, '2025-05-16 14:44:42', '2025-05-16 14:44:42'),
(359, 121, '[\"hill\",\"house\"]', '', 'a large hill with a house on top of it', 0, 0, 'img_68274f80f2f846.60780798.jpg', 'uploads/Images/img_68274f80f2f846.60780798.jpg', 0, '2025-05-16 14:45:20', '2025-05-16 14:45:20'),
(360, 122, '[\"painting\",\"landscape\"]', '', 'a painting of a black and white landscape', 1, 2, 'img_6827524f83a316.74550285.jpg', 'uploads/Images/img_6827524f83a316.74550285.jpg', 0, '2025-05-16 14:57:19', '2025-05-16 17:19:01'),
(361, 122, '[\"vase\",\"flower\",\"painting\"]', '', 'a painting of a flower in a vase', 1, 2, 'img_6827526303b848.65782670.jpg', 'uploads/Images/img_6827526303b848.65782670.jpg', 0, '2025-05-16 14:57:39', '2025-05-16 17:18:59'),
(362, 122, '[\"church\",\"painting\",\"people\",\"group\"]', '', 'a painting of a group of people in a church', 0, 0, 'img_68275277d41579.08721670.jpg', 'uploads/Images/img_68275277d41579.08721670.jpg', 0, '2025-05-16 14:57:59', '2025-05-16 14:57:59'),
(363, 122, '[\"city\",\"painting\",\"buildings\",\"water\"]', '', 'a painting of a city with buildings reflected in the water', 1, 2, 'img_6827529927d820.77583628.jpg', 'uploads/Images/img_6827529927d820.77583628.jpg', 0, '2025-05-16 14:58:33', '2025-05-16 17:20:09'),
(364, 122, '[\"ecosystem\"]', '', 'a group of trees', 0, 0, 'img_682753215262d3.78530012.jpg', 'uploads/Images/img_682753215262d3.78530012.jpg', 0, '2025-05-16 15:00:49', '2025-05-16 15:00:49'),
(365, 122, '[\"wall\",\"photographs\"]', '', 'a wall with black and white photographs on it', 0, 0, 'img_6827540fb7c7c3.19901497.jpg', 'uploads/Images/img_6827540fb7c7c3.19901497.jpg', 0, '2025-05-16 15:04:47', '2025-05-16 15:04:47'),
(366, 122, '[\"art\"]', '', 'a black and white photo of a woman in a black dress', 0, 0, 'img_6827541fc4b933.99984663.jpg', 'uploads/Images/img_6827541fc4b933.99984663.jpg', 0, '2025-05-16 15:05:03', '2025-05-16 15:05:03'),
(367, 122, '[\"graffiti\"]', '', 'a mural on the side of a building', 0, 0, 'img_68275430a9a3f0.72101045.jpg', 'uploads/Images/img_68275430a9a3f0.72101045.jpg', 0, '2025-05-16 15:05:20', '2025-05-16 15:05:20'),
(368, 122, '[\"painting\",\"paint\"]', '', 'a painting with pink and blue paint', 0, 0, 'img_6827545a8ff460.88426686.jpg', 'uploads/Images/img_6827545a8ff460.88426686.jpg', 0, '2025-05-16 15:06:02', '2025-05-16 15:06:02'),
(369, 122, '[\"architecture\"]', '', 'a building with a bunch of colorful flowers on the side', 0, 0, 'img_68275472897b96.77881102.jpg', 'uploads/Images/img_68275472897b96.77881102.jpg', 0, '2025-05-16 15:06:26', '2025-05-16 15:06:26'),
(370, 123, '[\"war\",\"afghanistan\"]', '', 'a soldier smokes a cigarette in the desert', 0, 0, 'img_6827577363dbe4.61265042.jpg', 'uploads/Images/img_6827577363dbe4.61265042.jpg', 1, '2025-05-16 15:19:15', '2025-05-16 15:19:15'),
(371, 123, '[\"gaza\"]', '', 'a group of people standing around a fire', 0, 0, 'img_6827578202e8a0.82032115.jpg', 'uploads/Images/img_6827578202e8a0.82032115.jpg', 0, '2025-05-16 15:19:30', '2025-05-16 15:19:30'),
(372, 123, '[\"afghanistan\"]', '', 'a soldier with a gun and a flag', 2, 1, 'img_68275792386b45.36872710.jpg', 'uploads/Images/img_68275792386b45.36872710.jpg', 1, '2025-05-16 15:19:46', '2025-05-16 17:17:23'),
(373, 123, '[\"gaza\"]', '', 'a view of the devastated city of mogad, iraq', 1, 0, 'img_682757a1258933.58008824.jpg', 'uploads/Images/img_682757a1258933.58008824.jpg', 0, '2025-05-16 15:20:01', '2025-05-16 15:21:56'),
(374, 123, '[\"gaza\"]', '', 'people walk through the rubble of a building in the city of aleppo', 1, 1, 'img_682757b7ea6587.07980269.jpg', 'uploads/Images/img_682757b7ea6587.07980269.jpg', 0, '2025-05-16 15:20:23', '2025-05-16 17:20:25'),
(375, 123, '[\"gaza\"]', '', 'a boy walks through the rubble of a building in the city of aleppo', 1, 1, 'img_682757c80269a1.96038312.jpg', 'uploads/Images/img_682757c80269a1.96038312.jpg', 0, '2025-05-16 15:20:40', '2025-05-16 17:20:23'),
(376, 123, '[\"cannons\",\"fort\",\"san\"]', '', 'cannons at fort san', 0, 0, 'img_682757e3793796.72626081.jpg', 'uploads/Images/img_682757e3793796.72626081.jpg', 0, '2025-05-16 15:21:07', '2025-05-16 15:21:07'),
(378, 123, '[\"gaza\"]', '', 'a man carries a child in the rubble of a street in the city of peshawar', 1, 1, 'img_68275808928b27.59279271.jpg', 'uploads/Images/img_68275808928b27.59279271.jpg', 0, '2025-05-16 15:21:44', '2025-05-16 17:20:19'),
(379, 123, '[\"gore\",\"blood\"]', '', 'a person washing their hands in a sink', 1, 0, 'img_682758fce12309.17375248.jpg', 'uploads/Images/img_682758fce12309.17375248.jpg', 1, '2025-05-16 15:25:48', '2025-05-16 15:26:11'),
(380, 113, '[\"flag\",\"river\",\"bridge\"]', '', 'a bridge over a river with a flag hanging from it', 0, 1, 'img_68275ea14d8253.79827305.jpg', 'uploads/Images/img_68275ea14d8253.79827305.jpg', 0, '2025-05-16 15:49:53', '2025-05-16 17:19:27'),
(381, 113, '[\"tourism\"]', '', 'a boat is going down a narrow canal', 1, 1, 'img_68275ed795d002.86911967.jpg', 'uploads/Images/img_68275ed795d002.86911967.jpg', 0, '2025-05-16 15:50:47', '2025-05-16 17:19:10'),
(382, 113, '[\"smart cities\"]', '', 'the city skyline at night', 0, 1, 'img_682761e8888ed9.04792735.jpg', 'uploads/Images/img_682761e8888ed9.04792735.jpg', 0, '2025-05-16 16:03:52', '2025-05-16 17:19:29'),
(383, 121, '[\"nature\",\"nature\"]', '', 'a tree is sitting on a rock in the middle of a river', 0, 0, 'img_68276419c4c2d9.99184103.jpg', 'uploads/Images/img_68276419c4c2d9.99184103.jpg', 0, '2025-05-16 16:13:13', '2025-05-16 16:13:13'),
(384, 121, '[\"electric vehicles\"]', '', 'a road with a yellow line in the middle', 0, 0, 'img_68276447a0e995.52783646.jpg', 'uploads/Images/img_68276447a0e995.52783646.jpg', 0, '2025-05-16 16:13:59', '2025-05-16 16:13:59'),
(385, 121, '[\"rock\",\"ocean\"]', '', 'a large rock in the ocean', 0, 0, 'img_68276463bf4577.71029053.jpg', 'uploads/Images/img_68276463bf4577.71029053.jpg', 0, '2025-05-16 16:14:27', '2025-05-16 16:14:27');

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
(258, 314, 121, '2025-05-16'),
(255, 318, 114, '2025-05-16'),
(253, 319, 114, '2025-05-16'),
(254, 321, 114, '2025-05-16'),
(256, 322, 114, '2025-05-16'),
(271, 325, 123, '2025-05-16'),
(250, 327, 121, '2025-05-16'),
(249, 332, 121, '2025-05-16'),
(269, 336, 123, '2025-05-16'),
(262, 337, 122, '2025-05-16'),
(245, 341, 120, '2025-05-16'),
(247, 342, 120, '2025-05-16'),
(275, 342, 121, '2025-05-16'),
(246, 343, 120, '2025-05-16'),
(257, 349, 114, '2025-05-16'),
(274, 354, 121, '2025-05-16'),
(260, 360, 122, '2025-05-16'),
(259, 361, 122, '2025-05-16'),
(261, 363, 122, '2025-05-16'),
(273, 372, 121, '2025-05-16'),
(270, 372, 123, '2025-05-16'),
(263, 373, 123, '2025-05-16'),
(264, 374, 123, '2025-05-16'),
(265, 375, 123, '2025-05-16'),
(266, 378, 123, '2025-05-16'),
(268, 379, 123, '2025-05-16'),
(272, 381, 113, '2025-05-16');

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
(254, 121, 322, '2025-05-16 14:48:40'),
(255, 121, 322, '2025-05-16 14:48:41'),
(256, 121, 330, '2025-05-16 14:48:41'),
(257, 122, 322, '2025-05-16 15:07:24'),
(258, 122, 319, '2025-05-16 15:07:25'),
(259, 122, 317, '2025-05-16 15:07:27'),
(260, 122, 316, '2025-05-16 15:07:28'),
(261, 122, 333, '2025-05-16 15:07:29'),
(262, 122, 334, '2025-05-16 15:07:31'),
(263, 122, 335, '2025-05-16 15:07:32'),
(264, 122, 324, '2025-05-16 15:07:34'),
(265, 122, 341, '2025-05-16 15:07:37'),
(266, 122, 328, '2025-05-16 15:07:38'),
(267, 122, 329, '2025-05-16 15:07:39'),
(268, 122, 340, '2025-05-16 15:07:40'),
(269, 122, 360, '2025-05-16 15:07:42'),
(270, 122, 361, '2025-05-16 15:07:44'),
(271, 122, 363, '2025-05-16 15:07:46'),
(272, 122, 352, '2025-05-16 15:07:52'),
(273, 122, 354, '2025-05-16 15:07:56'),
(274, 122, 355, '2025-05-16 15:07:58'),
(275, 122, 337, '2025-05-16 15:08:00'),
(277, 123, 372, '2025-05-16 15:37:07'),
(278, 121, 331, '2025-05-16 17:18:34'),
(279, 121, 314, '2025-05-16 17:18:37'),
(280, 121, 318, '2025-05-16 17:18:38'),
(281, 121, 321, '2025-05-16 17:18:40'),
(282, 121, 325, '2025-05-16 17:18:43'),
(283, 121, 333, '2025-05-16 17:18:45'),
(284, 121, 352, '2025-05-16 17:18:48'),
(285, 121, 354, '2025-05-16 17:18:50'),
(286, 121, 361, '2025-05-16 17:18:58'),
(287, 121, 360, '2025-05-16 17:19:01'),
(288, 121, 316, '2025-05-16 17:19:03'),
(289, 121, 341, '2025-05-16 17:19:06'),
(290, 121, 381, '2025-05-16 17:19:10'),
(291, 121, 335, '2025-05-16 17:19:13'),
(292, 121, 342, '2025-05-16 17:19:17'),
(293, 121, 315, '2025-05-16 17:19:24'),
(294, 121, 380, '2025-05-16 17:19:27'),
(295, 121, 382, '2025-05-16 17:19:29'),
(296, 121, 323, '2025-05-16 17:19:32'),
(297, 121, 326, '2025-05-16 17:19:34'),
(299, 121, 338, '2025-05-16 17:19:39'),
(300, 121, 339, '2025-05-16 17:19:42'),
(301, 121, 345, '2025-05-16 17:19:44'),
(302, 121, 346, '2025-05-16 17:19:46'),
(303, 121, 347, '2025-05-16 17:19:52'),
(304, 121, 348, '2025-05-16 17:19:55'),
(305, 121, 351, '2025-05-16 17:19:59'),
(306, 121, 336, '2025-05-16 17:20:01'),
(307, 121, 332, '2025-05-16 17:20:04'),
(308, 121, 329, '2025-05-16 17:20:05'),
(309, 121, 328, '2025-05-16 17:20:07'),
(310, 121, 363, '2025-05-16 17:20:09'),
(311, 121, 317, '2025-05-16 17:20:14'),
(312, 121, 349, '2025-05-16 17:20:16'),
(313, 121, 378, '2025-05-16 17:20:19'),
(314, 121, 375, '2025-05-16 17:20:23'),
(315, 121, 374, '2025-05-16 17:20:25'),
(316, 121, 319, '2025-05-16 17:20:26'),
(317, 121, 340, '2025-05-16 17:20:31'),
(318, 121, 334, '2025-05-16 17:20:33'),
(319, 121, 355, '2025-05-16 17:20:39');

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
(113, 'architecture', 3),
(113, 'nature', 2),
(113, 'tourism', 1),
(113, 'wars', 1),
(114, 'architecture', 1),
(114, 'tourism', 3),
(115, 'wedding', 3),
(116, 'architecture', 1),
(119, 'architecture', 2),
(120, 'graduation', 7),
(120, 'wars', 1),
(121, 'nature', 8),
(121, 'tourism', 2),
(122, 'architecture', 1),
(123, 'wars', 7);

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
(21, 119, 123, '2025-05-16'),
(22, 121, 113, '2025-05-16');

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
(117, 'waleed', '2025-05-16 13:53:39', '2025-05-16 13:54:11'),
(118, 'waleed-Istanbul', '2025-05-16 13:54:41', '2025-05-16 13:55:24');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=386;

--
-- AUTO_INCREMENT for table `image_view_logs`
--
ALTER TABLE `image_view_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
