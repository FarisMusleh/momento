-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 02:29 PM
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
  `picture` varchar(512) NOT NULL DEFAULT 'Uploads/ProfilePicture/img_1.jpg',
  `location` varchar(128) NOT NULL,
  `provider` enum('local','google','facebook') NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `account-type`, `picture`, `location`, `provider`, `provider_id`) VALUES
(1, 'faris', 'faris@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'uploads/ProfilePicture/img_67d42a2bb06150.23744246.jpg', '', 'local', NULL),
(3, 'ahmed', 'ahmed@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'img/pfp/img_1.jpg', '', 'local', NULL),
(4, 'khalid', 'khalid@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'img/pfp/img_1.jpg', '', 'local', NULL),
(5, 'sara', 'sara@gmail.com', '$2y$10$hIAC5wjbrfl/pousihYHZeA4EZ9nPTfOr7ufkGo5QQNPfePFbc9mu', 'business', 'uploads/ProfilePicture/img_67d428804a1fa1.24890431.jpg', '', 'local', NULL),
(10, 'test', 'farismusleh2032003@gmail.com', '', 'user', 'img/pfp/img_1.jpg', '', 'google', '113031318110845926527'),
(13, 'fff', 'farisassaf03@gmail.com', '', 'user', 'img/pfp/img_1.jpg', '', 'google', '115829613571035348563'),
(15, 'aaa', 'test@gmail.com', '$2y$10$fuZI1H4UiD9C8i0xEaKAcuUhLdAiwVHsm2MypdQyY6YxjTSA68g.q', 'business', 'img/pfp/img_1.jpg', '', 'local', NULL),
(16, 'sdf', 'ddsdsdd@gmaill.o', '$2y$10$zyWq0zkq7RTKXCv0unFRfOAmIRRi6hmCMHvA5jR/vMaeHl3gleV8u', 'user', 'img/pfp/img_1.jpg', '', 'local', NULL),
(17, 'fwf', 'farisss@gmail.com', '$2y$10$8HCOeHzSDrph0MabNhQ.geWGrnUM.6Zy0Jjxh9Se7Grohp/wka01e', 'user', 'img/pfp/img_1.jpg', '', 'local', NULL),
(18, 'bb', 'bb@gm.com', '321', 'business', 'img/pfp/img_1.jpg', '', 'local', NULL),
(19, 'kk', 'kk@gmail.com', '$2y$10$Ktf0qAokTe/H6oNQX77cCONDt1UALooJrwQpsm/0vvtl4KKlvQ2ae', 'business', 'img/pfp/img_1.jpg', '', 'local', NULL),
(20, 'abcc', 'abc@gmail.com', '$2y$10$xCiF7Jl048aHsIyDffYJD.swbkYzvgqpsZtcyIveeyzlnyWNU81BK', 'business', 'uploads/ProfilePicture/img_67d41c6f8e7ec4.95067510.jpg', '', 'local', NULL),
(21, 'a', 'a@gmail.com', '$2y$10$pZJTDlcaz9wcsQqyYiMBJO1WpPglm3kEh68ki2oUtnVagbhd4rpDa', 'user', 'uploads/ProfilePicture/img_67d429914dfc99.13621730.jpg', '', 'local', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business-profiles`
--

CREATE TABLE `business-profiles` (
  `id` int(11) NOT NULL,
  `first-name` varchar(32) NOT NULL,
  `last-name` varchar(32) DEFAULT NULL,
  `gender` enum('not specifed','male','female') DEFAULT 'not specifed',
  `dob` date DEFAULT NULL,
  `phone-num` int(10) DEFAULT NULL,
  `picture` varchar(512) DEFAULT 'img/pfp/img_1.jpg',
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
(15, 'yaser', 'khalid', 'male', '2005-03-03', 32132132, 'img/pfp/p7.jpeg', 'dsadfdsfas', 'Hello fjdiasfngiagnfuidagbnusiaodfgbasdflasdbfg'),
(18, 'faa', NULL, 'not specifed', NULL, NULL, 'img/pfp/img_1.jpg', '', ''),
(19, '', NULL, 'male', NULL, NULL, 'img/pfp/img_1.jpg', '', ''),
(20, '', NULL, 'male', NULL, NULL, 'img/pfp/img_1.jpg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user-id` int(11) NOT NULL,
  `label` varchar(512) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user-id`, `label`, `title`, `description`, `url`) VALUES
(1, 1, '', 'My first photo', 'gmhbikdfsfgsdfgasddfasf fadsf', 'uploads/d.jpg'),
(2, 1, '', 'My First Photo', 'fdasfdg dfgf dgfsadgv sag fd', 'uploads/C.jpg'),
(3, 1, '', '', '', 'uploads/OIP.jpeg'),
(4, 1, '', '', '', 'uploads/flowers-276014_640.jpg'),
(5, 3, '', '', '', 'uploads/flowers-276014_640.jpg'),
(6, 5, '', '', '', 'uploads/flowers-276014_640.jpg'),
(7, 4, '', '', '', 'uploads/flowers-276014_640.jpg'),
(8, 1, '', '', '', 'uploads/OIP.jpg'),
(9, 15, '', '', '', 'uploads/dragon-ball-z-round-black-white-wallpaper.jpg'),
(10, 1, '', '', '', 'uploads/Capture.PNG'),
(11, 1, '', '', '', 'uploads/Capture.PNG'),
(12, 1, '', '', '', 'uploads/199138.png'),
(13, 1, '', 'fa', 'fa', '/'),
(14, 1, '', '', '', 'uploads/mnpkvh8c7iwb1.png'),
(15, 1, '', '', '', 'uploads/wqa30so8tpzd1.png'),
(16, 1, '', '', '', 'uploads/img_67ccb6cec36366.01324395.jpg'),
(17, 1, '', '', '', 'uploads/img_67cdd050450211.88910092.png'),
(18, 1, '', '', '', 'uploads/img_67cdd768254ec5.14290942.png'),
(19, 1, '', '', '', 'uploads/img_67cdd81ac4c752.68679344.png'),
(21, 19, '', '', '', 'uploads/img_67d1d0b139d131.71348769.jpg'),
(22, 19, '', '', '', 'uploads/img_67d208e4c41be0.16199566.jpeg'),
(23, 19, '', '', '', 'uploads/img_67d2103bd18af6.28523206.jpeg'),
(24, 19, 'cats, pets', '', '', 'uploads/img_67d210ceb98c47.68704891.jpeg'),
(25, 19, 'ukraine, war', '', '', 'uploads/img_67d2110c7d9911.32548667.jpg'),
(26, 19, 'gaza', '', '', 'uploads/img_67d211bc12f742.44565181.jpg'),
(27, 19, 'architecture, afghanistan, palestine', '', '', 'uploads/img_67d211d09e2dc1.30147528.jpg'),
(28, 19, 'graduation', '', '', 'uploads/img_67d218f2aaba60.09655380.jpg'),
(29, 19, 'ukraine, war', '', '', 'uploads/img_67d21937ac57b0.51594634.jpg'),
(30, 19, 'kuwait', '', '', 'uploads/img_67d219fa52de33.28384796.jpg'),
(31, 19, 'cats, pets', '', '', 'uploads/img_67d21ab0d13178.58012856.jpg'),
(32, 20, 'ukraine, war', '', '', 'uploads/img_67d225ab48e369.34985032.jpg'),
(33, 20, 'cats, pets', '', '', 'uploads/img_67d225b0d71221.94592881.jpg'),
(34, 20, 'ukraine, war, gaza, afghanistan', '', '', 'uploads/img_67d225b86fd7e8.41535873.jpg'),
(35, 20, 'architecture, afghanistan, palestine', '', '', 'uploads/img_67d225c1c2f3a1.35168735.jpg'),
(36, 20, 'palestine', '', '', 'uploads/img_67d225da962945.56738329.png'),
(37, 20, 'gaza', '', '', 'uploads/img_67d225e5d75235.59501764.jpg'),
(38, 20, 'gaza', '', '', 'uploads/img_67d225f68d7658.37233005.jpg'),
(39, 20, 'anime, art, journalist, animation, war', '', '', 'uploads/img_67d22605bac933.51510173.jpg'),
(40, 20, 'graduation', '', '', 'uploads/img_67d22613074f62.20918854.jpg'),
(41, 1, 'ukraine, war', '', '', 'uploads/img_67d308e2b96ce8.61570688.jpg'),
(42, 1, 'anime, art, journalist, animation, war', '', '', 'uploads/img_67d308f202ba95.08696427.jpg'),
(43, 1, 'ukraine, war', '', '', 'uploads/img_67d39facba3a81.27286399.jpg'),
(44, 1, '', '', '', 'uploads/img_67d3af51c88404.78098137.jpg'),
(45, 1, '', '', '', 'uploads/img_67d3c39c25ee21.90101204.jpg'),
(46, 1, '', '', '', 'uploads/img_67d3c5b6bd50f8.69276253.png'),
(47, 1, '', '', '', 'uploads/img_67d3da26cf3376.12525390.jpg'),
(48, 1, '', '', '', 'uploads/img_67d3e4a165b9f5.19232250.jpg'),
(49, 1, '', '', '', 'uploads/img_67d3ef0b892962.26566542.png'),
(50, 1, '', '', '', 'uploads/img_67d4125a70a9d4.20854025.jpg'),
(51, 1, '', '', '', 'uploads/Images/img_67d4154759fec0.79405368.jpg'),
(52, 20, '', '', '', 'uploads/Images/img_67d41a098b4a17.48693979.jpg'),
(53, 5, '', '', '', 'uploads/Images/img_67d41f9f84bfe5.97168801.jpg'),
(54, 5, '', '', '', 'uploads/Images/img_67d41fa1987305.03473155.jpg'),
(55, 5, '', '', '', 'uploads/Images/img_67d41fa3a35050.87602494.jpg'),
(56, 5, '', '', '', 'uploads/Images/img_67d41fa5b70000.40687626.jpg'),
(57, 5, '', '', '', 'uploads/Images/img_67d41fa7ca25e4.36964454.jpg'),
(58, 5, '', '', '', 'uploads/Images/img_67d41fa9df5462.26044595.jpg'),
(59, 5, '', '', '', 'uploads/Images/img_67d41fabeb7d14.87874869.jpg'),
(60, 5, '', '', '', 'uploads/Images/img_67d420e4b224f0.75599448.jpg'),
(61, 5, '', '', '', 'uploads/Images/img_67d420e6c68287.57389165.jpg'),
(62, 5, '', '', '', 'uploads/Images/img_67d42127484775.96638334.jpg'),
(63, 5, '', '', '', 'uploads/Images/img_67d421295b23b8.15806359.jpg'),
(64, 5, '', '', '', 'uploads/Images/img_67d4212b6b0879.06466055.jpg'),
(65, 5, '', '', '', 'uploads/Images/img_67d422ebbc0738.57336695.jpg'),
(66, 5, '', '', '', 'uploads/Images/img_67d422f9321f39.59811117.jpg'),
(67, 5, '', '', '', 'uploads/Images/img_67d424c599ffb9.04211422.jpg'),
(68, 5, '', '', '', 'uploads/Images/img_67d424d6998818.46371789.jpg'),
(69, 5, '', '', '', 'uploads/Images/img_67d4259dca3454.97204845.jpg'),
(70, 5, '', '', '', 'uploads/Images/img_67d425ccdd3f41.00507215.jpg');

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
  `phone-num` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user-profiles`
--

INSERT INTO `user-profiles` (`id`, `first-name`, `last-name`, `gender`, `dob`, `phone-num`) VALUES
(16, NULL, NULL, 'male', NULL, NULL),
(17, NULL, NULL, 'male', NULL, NULL),
(21, NULL, NULL, 'male', NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

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
