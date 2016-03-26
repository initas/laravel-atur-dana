-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 26, 2016 at 07:51 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ad`
--

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon_class` varchar(20) NOT NULL,
  `hex_color` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`id`, `user_id`, `name`, `icon_class`, `hex_color`, `created_at`, `updated_at`, `status_id`) VALUES
(1, 1, 'ATM BCA', 'fa fa-heart', 'ffff00', '2016-03-15 10:32:36', '2016-03-15 10:34:13', 1),
(2, 1, 'Dompet', 'fa fa-heart', 'ffff00', '2016-03-15 10:32:36', '2016-03-15 10:34:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sources_collaborators`
--

CREATE TABLE `sources_collaborators` (
  `id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `transaction_category_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `description` text,
  `image_url` text,
  `geo_location` varchar(255) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `altitude` float DEFAULT NULL,
  `transaction_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `source_id`, `transaction_category_id`, `amount`, `description`, `image_url`, `geo_location`, `latitude`, `longitude`, `altitude`, `transaction_at`, `created_at`, `updated_at`, `status_id`) VALUES
(1, 1, 1, 1, 1, 'updated dummy description', NULL, NULL, NULL, NULL, NULL, '2016-03-13 17:55:17', '2016-03-11 20:10:48', '2016-03-15 10:53:55', 0),
(56, 2, 1, 1, 1, 'dummy description', NULL, '', 0, 0, 0, '0000-00-00 00:00:00', '2016-03-26 08:38:24', '2016-03-26 08:38:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_categories`
--

CREATE TABLE `transaction_categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `icon_class` varchar(20) NOT NULL,
  `hex_color` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_categories`
--

INSERT INTO `transaction_categories` (`id`, `user_id`, `name`, `icon_class`, `hex_color`, `created_at`, `updated_at`, `status_id`) VALUES
(1, NULL, 'makan', 'fa fa-cutlery', 'ffff00', '2016-03-12 17:34:01', '2016-03-12 21:04:17', 1),
(2, 1, 'dummy', 'fa fa-heart', 'ff00ff', '2016-03-12 14:07:30', '2016-03-12 14:07:30', 1),
(3, 1, 'dummy', 'fa fa-heart', 'ff00ff', '2016-03-12 14:07:38', '2016-03-12 14:07:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_comments`
--

CREATE TABLE `transaction_comments` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_comments`
--

INSERT INTO `transaction_comments` (`id`, `transaction_id`, `user_id`, `description`, `created_at`, `updated_at`, `status_id`) VALUES
(1, 1, 1, 1, '2016-03-12 17:40:39', '2016-03-12 17:40:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fb_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unique_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `image_url` text COLLATE utf8_unicode_ci,
  `cover_image_url` text COLLATE utf8_unicode_ci,
  `auth_token` text COLLATE utf8_unicode_ci,
  `point_total` int(11) NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fb_id`, `unique_id`, `email`, `username`, `password`, `full_name`, `birthday`, `gender_id`, `image_url`, `cover_image_url`, `auth_token`, `point_total`, `last_login_at`, `created_at`, `updated_at`, `status_id`) VALUES
(1, NULL, '1', 'admin@situs.id', 'admin', '2133fc4d88502222bbf42c116a20700b4d78e0f1cbd4b09e6f23460b4525fa7c2f', 'Inara Risyah', NULL, NULL, NULL, NULL, 'c7c3565bef6a5cf4c562d672687cd8e8', 0, '2016-02-11 00:27:45', '2016-02-04 09:43:25', '2016-03-12 19:03:20', 0),
(2, NULL, '2', 'febri@situs.id', 'mf', '2133fc4d88502222bbf42c116a20700b4d78e0f1cbd4b09e6f23460b4525fa7c2f', 'muhammad febriansyah', NULL, NULL, NULL, NULL, '7abcf6fe946c0ecbb0df9ed7ccc2a465', 0, NULL, '2016-02-04 09:43:25', '2016-03-12 19:03:14', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_category_id` (`transaction_category_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_comments`
--
ALTER TABLE `transaction_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `transaction_comments`
--
ALTER TABLE `transaction_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`transaction_category_id`) REFERENCES `transaction_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`source_id`) REFERENCES `sources` (`id`);

--
-- Constraints for table `transaction_comments`
--
ALTER TABLE `transaction_comments`
  ADD CONSTRAINT `transaction_comments_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
