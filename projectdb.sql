-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 08:54 PM
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
(30, 'b', 'b@g.c', '$2y$10$ff.o2apGdBxJMK.95p/5PefpkQE8GZg7mVGz5qnIfUyR5E8DJLZwG', 'business', '/momento/uploads/ProfilePicture/img_67fd94fc4fd380.19775689.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-03-20 13:59:39', '2025-04-19 00:36:56'),
(42, 'TEST', 'farisassaf03@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/img_67fed3ff0c5ad8.67954681.jpg', 'Azerbaijan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '115829613571035348563', '2025-04-10 13:05:32', '2025-04-19 00:36:56'),
(43, 'faris', 'a@g.c', '$2y$10$XoV0EHGKPhUjmZu4psviO.yJz401P3pC8AgsylUOSgnb.HS9NMX8C', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-11 23:16:52', '2025-04-19 00:36:56'),
(44, 'ahmed', 'c@g.c', '$2y$10$2iDXBE/6duAZAVXJLJsKC.l8F8U31txrJxzutju93mgcfT0vZO3G.', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-11 23:17:35', '2025-04-19 00:36:56'),
(45, 'r', 'h@c.g', '$2y$10$zn/mFGXBv5KplnQU9eDNlu2bk2SLxp9mZwZYfOkZuOQCfH4t2Yk2C', 'user', '/momento/Uploads/ProfilePicture/img_1.jpg', 'Albania', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-11 23:17:51', '2025-04-19 12:13:12'),
(46, 'te', 'h@h.c', '$2y$10$Vd7pV1Ens83dy80hcDAd4..GiZIt0TNqSovrLDBPS.c.8PS.P6MhS', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-11 23:18:08', '2025-04-19 00:36:56'),
(47, 'y', 'y@y.c', '$2y$10$z5g3NRJRSu754pEJ7YVbz.55KUVA5Sl6.muZMhoTH3DqPH2qiMCJi', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-11 23:18:22', '2025-04-19 00:36:56'),
(50, 'hj', 'hj@g.c', '$2y$10$Q31aPHgG1SN7P6eacjilZuyAn2e3gw/5VveU/3hq1EHj9JHrfehje', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-11 23:19:34', '2025-04-19 00:36:56'),
(58, 'ggg', 'l@g.c', '$2y$10$JLbQP1zVCu3yfxSeManAkObLQ5MaSylLzC5pLtv4xnFlcHATtb.Qy', 'business', 'Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-19 18:14:56', '2025-04-19 18:14:56'),
(59, 'mnm', 't@g.c', '$2y$10$CLeAtheWQB7a/ebAWF77Q.8pIW1pqZv0BtmjDT8n2VfheyY0pKrWa', 'business', '/momento/Uploads/ProfilePicture/img_1.jpg', '', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'local', NULL, '2025-04-19 18:27:38', '2025-04-19 18:27:38'),
(61, 'hgfdhgdf', 'farismusleh2032003@gmail.com', '', 'business', '/momento/uploads/ProfilePicture/google_113031318110845926527.jpg', 'Azerbaijan', 0.000000000000000000000000000000, 0.000000000000000000000000000000, 'google', '113031318110845926527', '2025-04-19 18:31:24', '2025-04-19 18:31:24');

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
  `rate` float NOT NULL DEFAULT 0,
  `total_likes` int(255) NOT NULL DEFAULT 0,
  `total_views` int(255) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_profiles`
--

INSERT INTO `business_profiles` (`id`, `business_name`, `gender`, `contact_number`, `social_links`, `bio`, `rate`, `total_likes`, `total_views`, `created_at`, `updated_at`) VALUES
(30, '', 'male', NULL, '{\"facebook\":\"https:\\/\\/web.facebook.com\\/\",\"twitter\":\"\",\"linked-in\":\"\",\"instagram\":\"\"}', '', 3, 0, 0, '2025-03-20 13:59:39', '2025-04-19 11:53:48'),
(42, 'Faris', 'not specifed', NULL, '{\"facebook\":\"https:\\/\\/web.facebook.com\\/\",\"twitter\":\"https:\\/\\/web.facebook.com\\/\",\"linked-in\":\"https:\\/\\/web.facebook.com\\/\",\"instagram\":\"https:\\/\\/web.facebook.com\\/\"}', 'Experienced software engineer with 8+ years in full-stack development. Specialized in JavaScript frameworks and cloud architecture. Passionate about creating scalable, maintainable solutions and mentoring junior developers.', 0, 0, 0, '2025-04-10 13:05:32', '2025-04-14 23:26:21'),
(43, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:16:52', '2025-04-11 23:16:52'),
(44, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:17:35', '2025-04-11 23:17:35'),
(46, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:18:08', '2025-04-11 23:18:08'),
(47, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-11 23:18:22', '2025-04-11 23:18:22'),
(58, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-19 18:14:56', '2025-04-19 18:14:56'),
(59, '', 'male', NULL, NULL, '', 0, 0, 0, '2025-04-19 18:27:38', '2025-04-19 18:27:38'),
(61, '̇Oshiro', 'not specifed', NULL, NULL, '', 0, 0, 0, '2025-04-19 18:31:24', '2025-04-19 18:31:24');

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
(1, 45, 42, '2025-04-18 12:59:34'),
(2, 42, 42, '2025-04-18 18:25:04'),
(3, 42, 30, '2025-04-18 20:37:56'),
(4, 30, 43, '2025-04-18 21:47:14'),
(5, 30, 44, '2025-04-19 10:13:18'),
(6, 30, 57, '2025-04-19 11:25:15');

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
(100, 30, 'anime, journalist, animation', '', '', 0, 3, 'uploads/Images/img_67df97e44460a4.02441688.jpg', '2025-03-23 05:11:00', '2025-04-14 22:27:49'),
(101, 30, 'architecture, travel, kuwait, cars', '', '', 0, 1, 'uploads/Images/img_67f58dc7c33d96.76963262.png', '2025-04-08 20:57:43', '2025-04-11 13:17:24'),
(102, 42, 'graduation, wedding', '', 'idk', 0, 6, 'uploads/Images/img_67f7d7cf03f8d2.85822539.jpg', '2025-04-10 14:38:07', '2025-04-14 22:27:45'),
(103, 42, 'pets, food, style, gaza, egypt', '', 'l', 0, 3, 'uploads/Images/img_67f7d870d4b772.81008854.jpg', '2025-04-10 14:40:48', '2025-04-17 19:43:06'),
(104, 42, 'palestine', '', 'p', 0, 19, 'uploads/Images/img_67f7d9fbd6d462.91561722.png', '2025-04-10 14:47:23', '2025-04-19 12:08:32'),
(105, 42, 'egypt, men, tourism, style, food', '', 'lion :)', 0, 4, 'uploads/Images/img_67f7ea1447f439.48649551.jpg', '2025-04-10 15:56:04', '2025-04-19 12:08:31'),
(106, 42, 'travel, egypt, tourism, events', '', 'ba', 0, 1, 'uploads/Images/img_67f7ea291e1648.08642139.jpg', '2025-04-10 15:56:25', '2025-04-14 22:27:41'),
(107, 42, 'pets, sports', '', 'dog', 0, 2, 'uploads/Images/img_67f7eb147f13c1.25239980.jpg', '2025-04-10 16:00:20', '2025-04-11 23:08:44'),
(108, 42, 'nature, travel, ukraine', '', 'test', 0, 4, 'uploads/Images/img_67f853545ccec7.36154339.jpg', '2025-04-10 23:25:08', '2025-04-14 22:28:00'),
(109, 54, 'egypt, men, tourism, style, food', '', 'lion', 0, 0, 'uploads/Images/img_67facb11d48503.44093740.jpg', '2025-04-12 20:20:33', '2025-04-12 20:20:33'),
(110, 55, 'egypt, men, tourism, style, food', '', 'test', 0, 0, 'uploads/Images/img_67fadf02b60f43.25272949.jpg', '2025-04-12 21:45:38', '2025-04-12 21:45:38'),
(111, 42, '', '', 'idk', 0, 0, 'uploads/Images/img_67fda912691067.66558349.jpg', '2025-04-15 00:32:18', '2025-04-18 17:40:51'),
(112, 42, '', '', 'idk', 0, 1, 'uploads/Images/img_67fdaa2bb91030.45251536.jpg', '2025-04-15 00:36:59', '2025-04-17 19:42:55'),
(113, 42, '', '', 'edited one', 0, 1, 'uploads/Images/img_67fdb3f0a2b935.59152173.jpg', '2025-04-15 01:18:40', '2025-04-15 20:45:53'),
(114, 42, '', '', 'kk', 0, 1, 'uploads/Images/img_67fdb4f173d676.10610569.jpg', '2025-04-15 01:22:57', '2025-04-17 19:42:57'),
(115, 42, '', '', '', 0, 1, 'uploads/Images/img_67fdb520c40db0.57705678.jpg', '2025-04-15 01:23:44', '2025-04-17 19:43:02'),
(116, 42, '', '', 'f', 0, 0, 'uploads/Images/img_67fdb65e8d8b36.50679922.jpg', '2025-04-15 01:29:02', '2025-04-15 01:29:02'),
(117, 42, '', '', 'z', 0, 1, 'uploads/Images/img_67fdb6acc574e3.91833949.jpg', '2025-04-15 01:30:20', '2025-04-15 20:16:17'),
(118, 42, '', '', 'k', 0, 0, 'uploads/Images/img_67fdb9cc1d5ff2.25424815.jpg', '2025-04-15 01:43:40', '2025-04-15 01:43:40'),
(119, 42, '', '', 'l', 0, 0, 'uploads/Images/img_67fdbaaca84a82.36798401.jpg', '2025-04-15 01:47:24', '2025-04-15 01:47:24'),
(120, 42, '', '', '', 0, 1, 'uploads/Images/img_67fdc6c8bcee52.48527377.jpg', '2025-04-15 02:39:04', '2025-04-15 20:36:11'),
(121, 42, '', '', 'test', 0, 1, 'uploads/Images/img_67fec0903e1392.31358983.jpg', '2025-04-15 20:24:48', '2025-04-15 20:45:21'),
(122, 42, '', '', 'e', 0, 0, 'uploads/Images/img_67fec0df235727.00598091.jpg', '2025-04-15 20:26:07', '2025-04-15 20:45:37'),
(123, 42, 'lion, animal, wildlife, lifestyle, business', '', 'AFTER RESTART', 0, 1, 'uploads/Images/img_67fec235c61b21.59373264.jpg', '2025-04-15 20:31:49', '2025-04-15 20:36:40'),
(124, 42, 'lion, wildlife, animal, natural language processing, business', '', 't', 0, 0, 'uploads/Images/img_67fec5d1d5a276.48490298.jpg', '2025-04-15 20:47:13', '2025-04-15 20:47:13'),
(125, 42, 'animal, pets, natural language processing, neural networks, wildlife', '', 'DOG AFTER RESTART', 0, 0, 'uploads/Images/img_67fec63bdd1a04.14750607.jpg', '2025-04-15 20:48:59', '2025-04-15 20:48:59'),
(126, 42, 'dog, wolf, natural language processing, deep learning, animal', '', 'DOG NEW AFTERRRR', 0, 1, 'uploads/Images/img_67fec6e8cca691.08290629.jpg', '2025-04-15 20:51:52', '2025-04-19 11:49:42'),
(127, 57, 'logo, university, afghanistan, students, education', '', 'zu logoooooo', 0, 0, 'uploads/Images/img_67fee18892cd12.01285696.jpg', '2025-04-15 22:45:28', '2025-04-15 22:45:28'),
(128, 57, 'universe, cosmic, human, space, medicine', '', 'ART', 0, 0, 'uploads/Images/img_67fee4f9bfce58.09814022.jpg', '2025-04-15 23:00:09', '2025-04-15 23:00:09'),
(129, 57, 'universe, astronomy, animation, human, cosmic', '', 'neeeeeeeeee', 0, 0, 'uploads/Images/img_67fee564b9cfe7.49466725.jpg', '2025-04-15 23:01:56', '2025-04-15 23:01:56'),
(130, 30, 'zarqa, university, logo, computer science, students', '', 'z', 0, 0, 'uploads/Images/img_6802b9c4d648c1.39468508.jpg', '2025-04-18 20:44:52', '2025-04-18 20:46:37'),
(131, 30, 'software, web development, programming, computer vision, natural language processing', '', '', 0, 0, 'uploads/Images/img_6802e807445e69.33879973.jpg', '2025-04-19 00:02:15', '2025-04-19 00:02:15'),
(132, 30, 'dragon ball, logo, panda, data, economy', '', '', 0, 1, 'uploads/Images/img_6802e817f1c4f3.73068716.jpg', '2025-04-19 00:02:31', '2025-04-19 00:03:19'),
(133, 30, 'panda, dragon ball, lion, economy, logo', '', '', 0, 1, 'uploads/Images/img_6802e85a08d3b0.91239625.jpg', '2025-04-19 00:03:38', '2025-04-19 11:01:18'),
(134, 30, 'logo, natural language processing, panda, news, economy', '', 'dfds', 0, 0, 'uploads/Images/img_6802e86e600a63.46108097.jpg', '2025-04-19 00:03:58', '2025-04-19 00:03:58');

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
(99, 54, 105, '2025-04-12 21:12:42'),
(100, 30, 106, '2025-04-14 22:27:41'),
(102, 30, 102, '2025-04-14 22:27:45'),
(104, 30, 108, '2025-04-14 22:28:00'),
(105, 42, 117, '2025-04-15 20:16:17'),
(107, 42, 113, '2025-04-15 20:36:10'),
(108, 42, 120, '2025-04-15 20:36:11'),
(109, 42, 121, '2025-04-15 20:36:13'),
(110, 42, 123, '2025-04-15 20:36:40'),
(113, 42, 112, '2025-04-17 19:42:55'),
(114, 42, 114, '2025-04-17 19:42:57'),
(115, 42, 115, '2025-04-17 19:43:02'),
(118, 30, 132, '2025-04-19 00:03:19'),
(119, 30, 133, '2025-04-19 11:01:18'),
(120, 30, 126, '2025-04-19 11:49:42'),
(122, 45, 105, '2025-04-19 12:08:31'),
(123, 45, 104, '2025-04-19 12:08:32');

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
(1, 1, 45, 'HAHAHA MMM', '2025-04-18 13:00:57', 1, NULL),
(2, 2, 42, 'LOLOLO', '2025-04-18 18:25:53', 1, NULL),
(3, 1, 42, 'gfdsgfsdg', '2025-04-18 18:26:53', 1, NULL),
(4, 3, 42, 'nigga', '2025-04-18 20:37:56', 127, NULL),
(5, 3, 42, 'niigggaaaaa', '2025-04-18 20:38:11', 127, NULL),
(6, 3, 30, 'what???', '2025-04-18 20:39:09', 1, NULL),
(7, 3, 30, 'HELLO 42 HOW ARE YOU', '2025-04-18 21:17:25', 1, NULL),
(8, 3, 30, '42 MY NAME IS GJNFIDSGNFIDSG', '2025-04-18 21:21:11', 1, NULL),
(9, 3, 30, 'FGHDUASGHBUJSDBGUFDSBGFUDSAGFSGFSDG  SGFD GF SD', '2025-04-18 21:21:29', 1, NULL),
(10, 3, 30, 'FDSA FDAS', '2025-04-18 21:21:31', 1, NULL),
(11, 3, 30, 'HELLO 42, THIS IS NEW MSG AFTER EDITING :)', '2025-04-18 21:26:56', 1, NULL),
(12, 3, 30, 'FUCK YOU', '2025-04-18 21:38:35', 1, NULL),
(13, 4, 30, 'HELLO FARIS', '2025-04-18 21:47:14', 127, NULL),
(14, 4, 43, 'HELLO B', '2025-04-18 21:50:29', 127, NULL),
(15, 4, 30, 'HOW ARE YOU?', '2025-04-18 21:51:20', 127, NULL),
(16, 4, 30, 'i am fine', '2025-04-18 21:59:20', 127, NULL),
(17, 4, 43, 'me fine', '2025-04-18 21:59:35', 127, NULL),
(18, 4, 30, 'fdsa', '2025-04-18 22:24:43', 127, NULL),
(19, 3, 30, 'fdas', '2025-04-18 22:24:49', 1, NULL),
(20, 4, 30, 'test', '2025-04-19 00:11:48', 127, NULL),
(21, 4, 30, 'fdasfdas', '2025-04-19 00:20:35', 127, NULL),
(22, 3, 30, 'fdsafdasfdasg fds', '2025-04-19 00:20:41', 1, NULL),
(23, 3, 30, 'fds', '2025-04-19 00:22:19', 1, NULL),
(24, 4, 30, 'rfas', '2025-04-19 00:38:14', 127, NULL),
(25, 4, 30, 'test', '2025-04-19 09:38:25', 127, NULL),
(26, 4, 30, 'HELLO FARIS', '2025-04-19 09:39:49', 127, NULL),
(27, 4, 30, 'TEST', '2025-04-19 09:40:09', 127, NULL),
(28, 4, 30, 'test', '2025-04-19 10:00:40', 1, '2025-04-19'),
(29, 4, 30, 'hi', '2025-04-19 10:00:43', 1, '2025-04-19'),
(30, 3, 30, 'helllooo', '2025-04-19 10:00:49', 1, NULL),
(31, 4, 30, 'hello man', '2025-04-19 10:01:07', 1, '2025-04-19'),
(32, 4, 30, 'AFTER EDIT', '2025-04-19 10:10:24', 1, '2025-04-19'),
(33, 4, 30, 'NEW', '2025-04-19 10:10:47', 1, NULL),
(34, 5, 30, 'hello ahmed', '2025-04-19 10:13:18', 1, NULL),
(35, 4, 30, 'HELLO AHMED AFTER EDIT', '2025-04-19 10:19:26', 1, '2025-04-19'),
(36, 4, 30, 'FDSAFDASG BTN', '2025-04-19 10:20:20', 1, '2025-04-19'),
(37, 4, 43, 'HELLO FARIS NEWWW', '2025-04-19 10:20:39', 1, '2025-04-19'),
(38, 4, 43, 'HELLO', '2025-04-19 10:21:45', 1, NULL),
(39, 4, 30, 'TEST NOTIF', '2025-04-19 10:22:33', 1, NULL),
(40, 4, 30, 'FADS', '2025-04-19 10:22:39', 1, NULL),
(41, 4, 30, 'test', '2025-04-19 10:28:13', 1, '2025-04-19'),
(42, 4, 30, 'asd', '2025-04-19 10:40:00', 1, '2025-04-19'),
(43, 4, 30, 'FDASFDAFDAS', '2025-04-19 10:43:25', 1, '2025-04-19'),
(44, 6, 30, 'hello dog', '2025-04-19 11:25:15', 1, NULL),
(45, 4, 43, 'hello b', '2025-04-19 11:43:45', 1, NULL),
(46, 4, 30, 'hello faris how are you', '2025-04-19 11:46:44', 1, '2025-04-19'),
(47, 4, 43, 'good', '2025-04-19 11:46:58', 1, '2025-04-19'),
(48, 4, 30, 'gfdsxfg', '2025-04-19 11:47:03', 1, '2025-04-19'),
(49, 4, 30, 'bvdfxgh', '2025-04-19 11:47:09', 1, NULL),
(50, 4, 43, 'fdsafda', '2025-04-19 11:55:39', 1, NULL),
(51, 4, 30, 'fdas', '2025-04-19 12:05:58', 1, '2025-04-19'),
(52, 6, 30, 'fdasfdg', '2025-04-19 12:06:04', 1, NULL),
(53, 1, 45, 'gfdsgfs', '2025-04-19 12:07:05', 1, NULL),
(54, 5, 44, 'test', '2025-04-19 13:11:54', 1, NULL),
(55, 5, 30, 'gfsd', '2025-04-19 13:12:30', 1, '2025-04-19'),
(56, 5, 30, 'fdsgdsafsa fasdfaf', '2025-04-19 13:15:30', 1, '2025-04-19'),
(57, 5, 30, 'hello ahmed', '2025-04-19 13:17:36', 1, NULL),
(58, 5, 44, 'test', '2025-04-19 13:25:35', 1, NULL),
(59, 5, 30, 'vc', '2025-04-19 13:26:27', 1, '2025-04-19'),
(60, 5, 30, 'gfds', '2025-04-19 13:26:34', 1, '2025-04-19'),
(61, 5, 30, 'فقبيل', '2025-04-19 14:19:17', 1, '2025-04-19'),
(62, 5, 30, 'tfds', '2025-04-19 15:06:55', 1, '2025-04-19'),
(63, 6, 30, 'gfsdgfs', '2025-04-19 17:54:03', 0, NULL),
(64, 5, 30, 'fdsa', '2025-04-19 17:56:07', 1, '2025-04-19'),
(65, 6, 30, 'fdsafas', '2025-04-19 17:57:57', 0, NULL),
(66, 5, 30, 'fdasfa', '2025-04-19 17:58:07', 1, '2025-04-19'),
(67, 5, 30, 'fds', '2025-04-19 17:58:13', 1, '2025-04-19'),
(68, 6, 30, 'gfds', '2025-04-19 17:59:38', 0, NULL),
(69, 5, 30, 'fdsafda', '2025-04-19 18:00:12', 1, '2025-04-19'),
(70, 6, 30, 'fgsdgsd', '2025-04-19 18:01:22', 0, NULL),
(71, 5, 30, 'ffdsa', '2025-04-19 18:03:21', 1, '2025-04-19'),
(72, 6, 30, 'fdasfdas', '2025-04-19 18:07:06', 0, NULL),
(73, 5, 30, 'fdsagf', '2025-04-19 18:07:14', 1, '2025-04-19'),
(74, 5, 30, 'fvc', '2025-04-19 18:07:30', 1, '2025-04-19'),
(75, 5, 44, 'f dsaf das', '2025-04-19 18:07:39', 1, '2025-04-19'),
(76, 5, 30, 'fdsafdas', '2025-04-19 18:09:00', 1, '2025-04-19'),
(77, 6, 30, 'fdasfdas', '2025-04-19 18:09:04', 0, NULL),
(78, 5, 30, 'fdsafdas', '2025-04-19 18:09:23', 1, '2025-04-19'),
(79, 5, 30, 'dfsaf gfds gfds', '2025-04-19 18:13:55', 1, '2025-04-19'),
(80, 6, 30, 'fdasf das f', '2025-04-19 18:13:58', 0, NULL),
(81, 5, 44, 'fdsa fdsa fdas', '2025-04-19 18:14:05', 1, NULL),
(82, 2, 42, 'ghfhd', '2025-04-19 18:16:32', 1, '2025-04-19'),
(83, 1, 42, 'cvszvv', '2025-04-19 18:22:04', 0, NULL);

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
(45, 'GFF', 'male', 'MY NAME IS FJDIAOSFDASGFDS', NULL, '2025-04-19 12:18:00', '2025-04-19 12:18:14');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

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
