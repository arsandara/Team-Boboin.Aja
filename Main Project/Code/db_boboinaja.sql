-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 04:16 PM
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
-- Database: `db_boboinaja`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(7, 'nanad', 'nanad@adminboboin.aja', 'boboinaja123', '2025-03-30 14:13:29', '2025-03-30 14:13:29'),
(8, 'ara', 'ara@adminboboin.aja', 'boboinaja123', '2025-03-30 14:13:45', '2025-03-30 14:13:45'),
(9, 'pinkan', 'pinkan@adminboboin.aja', 'boboinaja123', '2025-03-30 14:14:03', '2025-03-30 14:14:03'),
(10, 'alja', 'alja@adminboboin.aja', 'boboinaja123', '2025-03-30 14:14:20', '2025-03-30 14:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `persons` int(11) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `guest_name`, `email`, `persons`, `special_requests`, `room_id`, `check_in`, `check_out`, `total_price`, `created_at`) VALUES
(1, 'Nama Lengkap Pengguna', 'email@contoh.com', 2, 'Early Check In, Late Check Out, Extra Bed', 1, '2025-10-10', '2025-10-11', 4675000.00, '2025-03-30 13:22:20');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `persons` varchar(20) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `duration` varchar(20) NOT NULL,
  `special_requests` varchar(255) DEFAULT NULL,
  `price_per_night` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `status` enum('Confirmed','Pending','Cancelled') DEFAULT 'Confirmed',
  `wifi_password` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `room_type` enum('family','jacuzzi','pet_friendly','romantic') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `capacity` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT 4.5,
  `breakfast_included` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `name`, `room_type`, `price`, `is_available`, `created_at`, `updated_at`, `capacity`, `rating`, `breakfast_included`) VALUES
(1, 'Deluxe Cabin', 'family', 700000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 2, 4.9, 1),
(2, 'Executive Cabin', 'family', 900000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 2, 4.7, 1),
(3, 'Executive Cabin with Jacuzzi', 'jacuzzi', 1250000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 2, 4.8, 1),
(4, 'Family Cabin', 'family', 1100000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 4, 4.8, 1),
(5, 'Family Cabin with Jacuzzi', 'jacuzzi', 1500000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 4, 4.9, 1),
(6, '2 Paws Cabin', 'pet_friendly', 750000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 2, 4.7, 1),
(7, '4 Paws Cabin', 'pet_friendly', 1000000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 4, 4.8, 1),
(8, 'Romantic Cabin', 'romantic', 1150000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 2, 4.8, 1),
(9, 'Romantic Cabin with Jacuzzi', 'romantic', 1650000.00, 1, '2025-03-30 08:00:40', '2025-03-30 08:00:40', 2, 4.8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms_backup`
--

CREATE TABLE `rooms_backup` (
  `room_id` int(11) NOT NULL DEFAULT 0,
  `room_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_bookings_room` (`room_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

QUERY PERBAIKAN (RATING DAN BREAKFAST)

UPDATE `rooms` 
SET `rating` = 4.9, 
    `breakfast_included` = `capacity`;

ALTER TABLE rooms ADD COLUMN image_booking VARCHAR(255) NULL;

UPDATE rooms SET image_booking = 'images-booking/room_1.jpeg' WHERE room_id = 1;
UPDATE rooms SET image_booking = 'images-booking/room_2.jpeg' WHERE room_id = 2;
UPDATE rooms SET image_booking = 'images-booking/room_3.jpeg' WHERE room_id = 3;
UPDATE rooms SET image_booking = 'images-booking/room_4.jpeg' WHERE room_id = 4;
UPDATE rooms SET image_booking = 'images-booking/room_5.jpeg' WHERE room_id = 5;
UPDATE rooms SET image_booking = 'images-booking/room_6.jpeg' WHERE room_id = 6;
UPDATE rooms SET image_booking = 'images-booking/room_7.jpeg' WHERE room_id = 7;
UPDATE rooms SET image_booking = 'images-booking/room_8.jpeg' WHERE room_id = 8;
UPDATE rooms SET image_booking = 'images-booking/room_9.jpeg' WHERE room_id = 9;

ALTER TABLE rooms ALTER COLUMN image_booking SET DEFAULT 'default-booking.jpg';

UPDATE rooms SET image_booking = 'images-booking/room_1.jpeg.jpeg' WHERE room_id = 1;
UPDATE rooms SET image_booking = 'images-booking/room_2.jpeg.png' WHERE room_id = 2;
UPDATE rooms SET image_booking = 'images-booking/room_3.jpeg.png' WHERE room_id = 3;
UPDATE rooms SET image_booking = 'images-booking/room_4.jpeg.png' WHERE room_id = 4;
UPDATE rooms SET image_booking = 'images-booking/room_5.jpeg.png' WHERE room_id = 5;
UPDATE rooms SET image_booking = 'images-booking/room_6.jpeg.png' WHERE room_id = 6;
UPDATE rooms SET image_booking = 'images-booking/room_7.jpeg.png' WHERE room_id = 7;
UPDATE rooms SET image_booking = 'images-booking/room_8.jpeg.png' WHERE room_id = 8;
UPDATE rooms SET image_booking = 'images-booking/room_9.jpeg.png' WHERE room_id = 9;




