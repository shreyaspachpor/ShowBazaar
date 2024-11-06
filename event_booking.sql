-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 09:52 AM
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
-- Database: `event_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`) VALUES
(1, 'Shreyas', 'shreyas123', 'shreyaspachporr@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `carousel_items`
--

CREATE TABLE `carousel_items` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel_items`
--

INSERT INTO `carousel_items` (`id`, `event_id`, `image_url`, `caption`, `created_at`) VALUES
(1, 1, 'images/events/singham.png', 'Special screening of Singham Again', '2024-11-06 06:38:33'),
(2, 2, 'images/events/bhoolbhulaiya3.png', 'Bhool Bhulaiyaa 3 Movie Night', '2024-11-06 06:38:33'),
(3, 3, 'images/events/bassi_show.png', 'Stand-up Comedy by Anubhav Singh Bassi', '2024-11-06 06:38:33'),
(4, 4, 'images/events/zakir_khan_taping.png', 'Experience the magic of Zakir Khan live with a special taping of his latest show', '2024-11-06 08:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Movies', 'A variety of films across different genres.'),
(2, 'Sports', 'Live and recorded sports events.'),
(3, 'Comedy', 'Comedy shows and stand-up events.'),
(4, 'Concerts', 'Live musical performances by various artists.');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `event_datetime` datetime DEFAULT NULL,
  `price_platinum` decimal(10,2) DEFAULT NULL,
  `price_gold` decimal(10,2) DEFAULT NULL,
  `price_silver` decimal(10,2) DEFAULT NULL,
  `total_seats` int(11) DEFAULT NULL,
  `available_seats` int(11) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `category_id`, `title`, `description`, `image_url`, `venue`, `event_datetime`, `price_platinum`, `price_gold`, `price_silver`, `total_seats`, `available_seats`, `status`, `created_at`) VALUES
(1, 1, 'Singham Again', 'Special screening of the action-packed film Singham Again', 'images/events/singham.png', 'Cinema Paradise', '2024-11-15 19:30:00', 600.00, 400.00, 250.00, 300, 300, 'upcoming', '2024-11-06 06:26:48'),
(2, 1, 'Bhool Bhulaiyaa 3 Movie Night', 'Enjoy a thrilling screening of the much-awaited Bhool Bhulaiyaa 3', 'images/events/bhoolbhulaiyaa3.png', 'IMAX Theatre', '2024-11-18 20:00:00', 700.00, 500.00, 300.00, 250, 250, 'upcoming', '2024-11-06 06:26:48'),
(3, 3, 'Kisi Ko Batana Mat Ft. Anubhav Singh Bassi', 'An exclusive stand-up comedy show featuring Anubhav Singh Bassi', 'images/events/bassi_show.png', 'Laugh Factory', '2024-11-22 19:00:00', 900.00, 600.00, 400.00, 200, 200, 'upcoming', '2024-11-06 06:26:48'),
(4, 3, 'Zakir Khan Live - Special Taping', 'Experience the magic of Zakir Khan live with a special taping of his latest show', 'images/events/zakir_khan_taping.png', 'City Arena', '2024-12-05 18:30:00', 1000.00, 750.00, 500.00, 400, 400, 'upcoming', '2024-11-06 06:26:48'),
(5, 2, 'Corporates Road To Dubai', 'An exclusive event for corporate professionals with networking opportunities and insights on Dubai market expansion', 'images/events/corporates_road_dubai.png', 'Business Hub Convention Center', '2024-12-10 10:00:00', 2000.00, 1500.00, 1000.00, 300, 300, 'upcoming', '2024-11-06 06:26:48'),
(6, 2, 'Pro Kabaddi League Season 11 - Pune', 'Experience the thrill of Pro Kabaddi League Season 11 live in Pune!', 'images/events/pro_kabaddi_pune.png', 'Pune Stadium', '2024-12-15 18:00:00', 1200.00, 800.00, 500.00, 500, 500, 'upcoming', '2024-11-06 06:26:48'),
(7, 4, 'The Coldplay Experience (A Tribute to Coldplay)', 'An electrifying tribute to Coldplay, performing their greatest hits live!', 'images/events/coldplay_tribute.png', 'Blue Note Club', '2024-12-20 21:00:00', 1000.00, 700.00, 500.00, 300, 300, 'upcoming', '2024-11-06 06:26:48'),
(8, 4, 'Lollapalooza India 2025', 'Join us for an unforgettable experience at Lollapalooza India 2025, featuring world-class artists and performances!', 'images/events/lollapalooza_india_2025.png', 'Mahalaxmi Race Course', '2025-01-30 12:00:00', 1500.00, 1000.00, 700.00, 5000, 5000, 'upcoming', '2024-11-06 06:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(1, 'shreyas', 'shreyas.pachpor@somaiya.edu', 'shreyas', '8888888888888', '2024-11-06 02:10:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carousel_items`
--
ALTER TABLE `carousel_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_id` (`event_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carousel_items`
--
ALTER TABLE `carousel_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carousel_items`
--
ALTER TABLE `carousel_items`
  ADD CONSTRAINT `carousel_items_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_event_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
