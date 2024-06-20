-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2024 at 07:38 AM
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
-- Database: `schedule_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `user_id`, `title`, `start_date`, `end_date`, `description`, `location`, `created_at`, `updated_at`) VALUES
(1, 1, 'santhosh', '2024-06-19 20:46:00', '2024-06-19 00:40:00', NULL, NULL, '2024-06-18 12:13:41', '2024-06-19 03:06:28'),
(2, 1, 'work', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, '2024-06-18 12:14:09', '2024-06-18 12:15:09'),
(5, 1, '', '2024-06-18 21:50:00', '2024-06-18 10:51:00', NULL, NULL, '2024-06-18 12:16:13', '2024-06-18 12:16:13'),
(7, 1, 'santhosh', '2024-06-18 19:55:00', '2024-06-18 19:55:00', NULL, NULL, '2024-06-18 12:23:55', '2024-06-18 12:23:55'),
(8, 1, 'santhosh', '2024-06-18 20:57:00', '2024-06-18 20:57:00', NULL, NULL, '2024-06-18 12:24:08', '2024-06-18 12:24:08'),
(10, 1, 'work', '2024-06-19 10:33:00', '2024-06-19 00:35:00', NULL, NULL, '2024-06-19 03:01:35', '2024-06-19 03:01:35'),
(11, 1, 'gym', '2024-06-19 11:34:00', '2024-06-19 00:35:00', NULL, NULL, '2024-06-19 03:01:54', '2024-06-19 03:01:54'),
(12, 1, 'work', '2024-06-19 11:35:00', '2024-06-19 00:36:00', NULL, NULL, '2024-06-19 03:02:27', '2024-06-19 03:02:27'),
(13, 1, 'santhosh', '2024-06-26 11:39:00', '2024-06-26 00:40:00', NULL, NULL, '2024-06-19 03:06:20', '2024-06-19 03:06:20'),
(14, 1, 'work', '2024-06-28 10:38:00', '2024-06-28 00:40:00', NULL, NULL, '2024-06-19 03:06:52', '2024-06-19 03:06:52'),
(15, 1, 'santhosh', '2024-06-10 10:38:00', '2024-06-10 00:41:00', NULL, NULL, '2024-06-19 03:07:05', '2024-06-19 03:07:05'),
(16, 1, 'gym', '2024-06-12 10:00:00', '2024-06-12 17:58:00', NULL, NULL, '2024-06-19 03:28:59', '2024-06-19 03:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'nexglimpse@gmail.com', '$2y$10$.Pbkn9T0YQbX4YeACbtZhu21JPv5iadJH3cTEIhML1plAvB5pSLLi'),
(6, 'santhosh', 'santhoshr0415@gmail.com', '$2y$10$egP/XWEVppk0K.WiArbUcuhssjOBrlj58PrfuBYpZWwlG81Cw6Sae'),
(7, 'ajay', 'ajay123@gmail.com', '$2y$10$mLy1C.xAxxGAT5l3QZHtheK6ZcNrpQBvUmLLaiL7mgJUfRhqIjwKS'),
(8, 'gopi', 'pcasd21ca081@gmail.com', '$2y$10$CJWFANw2ZRyeHyo1tK.2suwWyTQf8ztXWK880ZGCmpuauczvJcr2C');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
