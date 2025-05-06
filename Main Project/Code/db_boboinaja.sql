-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 11:05 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `assign_room_number` (IN `p_room_id` INT, OUT `p_room_number` VARCHAR(10))   BEGIN
    DECLARE room_prefix VARCHAR(5);
    DECLARE next_number INT;
    
    
    SELECT 
        CASE 
            WHEN room_type = 'standard' AND room_name LIKE 'Deluxe%' THEN 'D'
            WHEN room_type = 'standard' AND room_name LIKE 'Executive%' THEN 'E'
            WHEN room_type = 'family' THEN 'F'
            WHEN room_type = 'pet_friendly' THEN 'P'
            WHEN room_type = 'romantic' THEN 'R'
            WHEN room_type = 'jacuzzi' THEN 'J'
            ELSE 'X'
        END INTO room_prefix
    FROM rooms 
    WHERE room_id = p_room_id;

SELECT 
        IFNULL(MAX(CAST(SUBSTRING(room_number, 2) AS UNSIGNED)), 0) + 1 INTO next_number
    FROM reservations
    WHERE room_number LIKE CONCAT(room_prefix, '%')
    AND room_id = p_room_id;
    
    
    SET p_room_number = CONCAT(room_prefix, LPAD(next_number, 3, '0'));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(7, '', 'nanad@adminboboin.aja', 'boboinaja123', '2025-03-30 07:13:29', '2025-03-30 07:13:29'),
(8, '', 'ara@adminboboin.aja', 'boboinaja123', '2025-03-30 07:13:45', '2025-03-30 07:13:45'),
(9, '', 'pinkan@adminboboin.aja', 'boboinaja123', '2025-03-30 07:14:03', '2025-03-30 07:14:03'),
(10, '', 'alja@adminboboin.aja', 'boboinaja123', '2025-03-30 07:14:20', '2025-03-30 07:14:20');

-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`


-- --------------------------------------------------------

--
-- Table structure for table `job_batches`


-- --------------------------------------------------------

--
-- Table structure for table `migrations`

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `guest_email` varchar(100) NOT NULL,
  `guest_phone` varchar(20) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `person` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `duration` int(11) NOT NULL,
  `early_checkin` tinyint(1) DEFAULT 0,
  `late_checkout` tinyint(1) DEFAULT 0,
  `extra_bed` tinyint(1) DEFAULT 0,
  `other_request` text DEFAULT NULL,
  `base_price` decimal(15,2) NOT NULL,
  `request_price` decimal(15,2) DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL,
  `tax` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `status` enum('Confirmed','Checked In','Checked Out','Cancelled') DEFAULT 'Confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `guest_name`, `guest_email`, `guest_phone`, `room_id`, `room_name`, `room_number`, `person`, `check_in`, `check_out`, `duration`, `early_checkin`, `late_checkout`, `extra_bed`, `other_request`, `base_price`, `request_price`, `subtotal`, `tax`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', 'budi@email.com', '08123456781', 1, 'Deluxe Cabin', '212', 2, '2025-05-03', '2025-05-05', 2, 1, 0, 0, NULL, 1400000.00, 350000.00, 1750000.00, 175000.00, 1925000.00, 'Checked In', '2025-05-02 12:03:10', '2025-05-02 12:05:50'),
(2, 'Ani Wijaya', 'ani@email.com', '08123456782', 3, 'Executive Cabin with Jacuzzi', '104', 2, '2025-05-03', '2025-05-05', 2, 0, 1, 1, NULL, 2500000.00, 650000.00, 3150000.00, 315000.00, 3465000.00, 'Checked In', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(3, 'Citra Dewi', 'citra@email.com', '08123456783', 5, 'Family Cabin with Jacuzzi', '112', 4, '2025-05-03', '2025-05-05', 2, 1, 1, 1, NULL, 3000000.00, 1000000.00, 4000000.00, 400000.00, 4400000.00, 'Checked In', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(4, 'Dedi Pratama', 'dedi@email.com', '08123456784', 2, 'Executive Cabin', '237', 2, '2025-05-03', '2025-05-06', 3, 0, 0, 0, NULL, 2700000.00, 0.00, 2700000.00, 270000.00, 2970000.00, 'Checked In', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(5, 'Eva Nurlela', 'eva@email.com', '08123456785', 4, 'Family Cabin', '252', 4, '2025-05-06', '2025-05-10', 4, 1, 0, 1, NULL, 4400000.00, 950000.00, 5350000.00, 535000.00, 5885000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(6, 'Fajar Setiawan', 'fajar@email.com', '08123456786', 6, '2 Paws Cabin', '257', 2, '2025-05-08', '2025-05-11', 3, 0, 1, 0, NULL, 2250000.00, 350000.00, 2600000.00, 260000.00, 2860000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(7, 'Gita Permata', 'gita@email.com', '08123456787', 7, '4 Paws Cabin', '117', 4, '2025-05-10', '2025-05-13', 3, 1, 1, 1, NULL, 3000000.00, 1150000.00, 4150000.00, 415000.00, 4565000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(8, 'Hendra Kurnia', 'hendra@email.com', '08123456788', 8, 'Romantic Cabin', '265', 2, '2026-01-02', '2026-01-04', 2, 0, 0, 0, NULL, 2300000.00, 0.00, 2300000.00, 230000.00, 2530000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(9, 'Indra Maulana', 'indra@email.com', '08123456789', 9, 'Romantic Cabin with Jacuzzi', '126', 2, '2026-01-10', '2026-01-13', 3, 1, 1, 0, NULL, 4950000.00, 700000.00, 5650000.00, 565000.00, 6215000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(10, 'Joko Susilo', 'joko@email.com', '08123456710', 1, 'Deluxe Cabin', '217', 2, '2026-01-15', '2026-01-20', 5, 0, 1, 1, NULL, 3500000.00, 1100000.00, 4600000.00, 460000.00, 5060000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(11, 'Kartika Sari', 'kartika@email.com', '08123456711', 3, 'Executive Cabin with Jacuzzi', '105', 2, '2026-01-20', '2026-01-23', 3, 1, 0, 0, NULL, 3750000.00, 350000.00, 4100000.00, 410000.00, 4510000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(12, 'Luki Hermawan', 'luki@email.com', '08123456712', 5, 'Family Cabin with Jacuzzi', '110', 4, '2026-01-25', '2026-01-29', 4, 0, 1, 1, NULL, 6000000.00, 950000.00, 6950000.00, 695000.00, 7645000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(13, 'Mira Agustina', 'mira@email.com', '08123456713', 7, '4 Paws Cabin', '119', 4, '2025-07-15', '2025-07-18', 3, 1, 1, 1, NULL, 3000000.00, 1150000.00, 4150000.00, 415000.00, 4565000.00, 'Confirmed', '2025-05-02 12:03:10', '2025-05-02 12:03:49'),
(14, 'Budi Santoso', 'budi@email.com', '08123456781', 1, 'Deluxe Cabin', '217', 2, '2025-05-03', '2025-05-05', 2, 1, 0, 0, NULL, 1400000.00, 350000.00, 1750000.00, 175000.00, 1925000.00, 'Checked In', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(15, 'Ani Wijaya', 'ani@email.com', '08123456782', 3, 'Executive Cabin with Jacuzzi', '106', 2, '2025-05-03', '2025-05-06', 3, 0, 1, 1, NULL, 3750000.00, 800000.00, 4550000.00, 455000.00, 5005000.00, 'Checked In', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(16, 'Citra Dewi', 'citra@email.com', '08123456783', 5, 'Family Cabin with Jacuzzi', '109', 4, '2025-04-28', '2025-05-01', 3, 1, 1, 1, NULL, 4500000.00, 1150000.00, 5650000.00, 565000.00, 6215000.00, 'Checked Out', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(17, 'Dedi Pratama', 'dedi@email.com', '08123456784', 2, 'Executive Cabin', '240', 2, '2025-04-30', '2025-05-02', 2, 0, 0, 0, NULL, 1800000.00, 0.00, 1800000.00, 180000.00, 1980000.00, 'Checked Out', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(18, 'Eva Nurlela', 'eva@email.com', '08123456785', 4, 'Family Cabin', '253', 4, '2025-05-04', '2025-05-07', 3, 1, 0, 1, NULL, 3300000.00, 800000.00, 4100000.00, 410000.00, 4510000.00, 'Confirmed', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(19, 'Fajar Setiawan', 'fajar@email.com', '08123456786', 6, '2 Paws Cabin', '252', 2, '2025-05-04', '2025-05-06', 2, 0, 1, 0, NULL, 1500000.00, 350000.00, 1850000.00, 185000.00, 2035000.00, 'Confirmed', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(20, 'Gita Permata', 'gita@email.com', '08123456787', 7, '4 Paws Cabin', '117', 4, '2025-05-10', '2025-05-13', 3, 1, 1, 1, NULL, 3000000.00, 1150000.00, 4150000.00, 415000.00, 4565000.00, 'Confirmed', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(21, 'Hendra Kurnia', 'hendra@email.com', '08123456788', 8, 'Romantic Cabin', '266', 2, '2025-06-02', '2025-06-05', 3, 0, 0, 0, NULL, 3450000.00, 0.00, 3450000.00, 345000.00, 3795000.00, 'Confirmed', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(22, 'Indra Maulana', 'indra@email.com', '08123456789', 9, 'Romantic Cabin with Jacuzzi', '128', 2, '2025-12-15', '2025-12-18', 3, 1, 1, 0, NULL, 4950000.00, 700000.00, 5650000.00, 565000.00, 6215000.00, 'Confirmed', '2025-05-02 12:03:35', '2025-05-02 12:03:49'),
(0, 'Ni\'amurizqi Setiawan', 'kuliahpurapura@gmail.com', '+6289106565446', 3, 'Executive Cabin with Jacuzzi', '107', 2, '2025-04-14', '2025-04-19', 5, 0, 1, 0, NULL, 6250000.00, 350000.00, 6600000.00, 660000.00, 7260000.00, 'Confirmed', '2025-05-06 00:54:08', '2025-05-06 00:54:08'),
(0, 'araa arsanda', 'kuliahpurapura@gmail.com', '+6289106565446', 1, 'Deluxe Cabin', '215', 2, '2025-04-14', '2025-04-19', 5, 0, 1, 0, NULL, 3500000.00, 350000.00, 3850000.00, 385000.00, 4235000.00, 'Confirmed', '2025-05-06 00:55:04', '2025-05-06 00:55:04');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) DEFAULT NULL,
  `room_number` varchar(10) DEFAULT NULL,
  `room_type` enum('family','jacuzzi','pet_friendly','romantic','standard') DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `capacity` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT 4.5,
  `breakfast_included` tinyint(1) DEFAULT 1,
  `image_booking` varchar(255) DEFAULT 'default-booking.jpg',
  `image_room` varchar(255) DEFAULT NULL,
  `total_rooms` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `room_number`, `room_type`, `price`, `is_available`, `created_at`, `updated_at`, `capacity`, `rating`, `breakfast_included`, `image_booking`, `image_room`, `total_rooms`) VALUES
(1, 'Deluxe Cabin', '215', 'standard', 700000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 2, 4.9, 2, 'images-booking/room_1.jpeg.jpeg', 'images-booking/room-1.png', 21),
(2, 'Executive Cabin', '225', 'standard', 900000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 2, 4.9, 2, 'images-booking/room_2.jpeg.png', 'images-booking/room-2.png', 20),
(3, 'Executive Cabin with Jacuzzi', '107', 'jacuzzi', 1250000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 2, 4.9, 2, 'images-booking/room_3.jpeg.png', 'images-booking/room-3.png', 9),
(4, 'Family Cabin', '246', 'family', 1100000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 4, 4.9, 4, 'images-booking/room_4.jpeg.png', 'images-booking/room-4.png', 15),
(5, 'Family Cabin with Jacuzzi', '112', 'family', 1500000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 4, 4.9, 4, 'images-booking/room_5.jpeg.png', 'images-booking/room-5.png', 7),
(6, '2 Paws Cabin', '255', 'pet_friendly', 750000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 2, 4.9, 2, 'images-booking/room_6.jpeg.png', 'images-booking/room-6.png', 12),
(7, '4 Paws Cabin', '119', 'pet_friendly', 1000000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 4, 4.9, 4, 'images-booking/room_7.jpeg.png', 'images-booking/room-7.png', 7),
(8, 'Romantic Cabin', '123', 'romantic', 1150000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 2, 4.9, 2, 'images-booking/room_8.jpeg.png', 'images-booking/room-8.png', 10),
(9, 'Romantic Cabin with Jacuzzi', '129', 'romantic', 1650000.00, 1, '2025-03-29 18:00:40', '2025-05-02 12:06:11', 2, 4.9, 2, 'images-booking/room_9.jpeg.png', 'images-booking/room-9.png', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) DEFAULT NULL,
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
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
ALTER TABLE `admins`
  MODIFY `admin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
