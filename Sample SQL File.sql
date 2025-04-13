-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2025 at 01:56 PM
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
-- Database: `ci3_assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `status` enum('New','In Progress','Closed') DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `email`, `phone`, `project_id`, `status`, `assigned_to`, `assigned_at`, `created_at`, `updated_at`) VALUES
(7, 'chekc', 'check@check.com', '123123123', 4, 'New', 3, '2025-04-13 13:01:35', '2025-04-13 13:01:35', '2025-04-13 13:01:35'),
(8, 'Mike', 'Mike@mike.com', '123123123', 5, 'In Progress', 2, '2025-04-13 13:16:29', '2025-04-13 13:16:29', '2025-04-13 13:16:29'),
(9, 'james', 'James@gmail.com', '123123123', 3, 'New', 2, '2025-04-13 13:39:48', '2025-04-13 13:39:48', '2025-04-13 13:39:48'),
(10, 'Happy', 'Happy@happy.com', '123123123', 6, 'New', 2, '2025-04-13 13:41:15', '2025-04-13 13:41:15', '2025-04-13 13:41:15'),
(11, 'orange', 'orange@orange.com', '12312321', 7, 'New', 2, '2025-04-13 13:51:50', '2025-04-13 13:51:50', '2025-04-13 13:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `round_robin_users`
--

CREATE TABLE `round_robin_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `projects` varchar(255) DEFAULT NULL,
  `order_no` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `round_robin_users`
--

INSERT INTO `round_robin_users` (`id`, `user_id`, `projects`, `order_no`) VALUES
(3, 3, '4', 2),
(4, 2, NULL, 9),
(5, 4, NULL, 7),
(6, 5, NULL, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `status`, `created_at`, `updated_at`) VALUES
(2, 'test1', 'test@test.com', 'Active', '2025-04-13 10:35:50', '2025-04-13 10:35:50'),
(3, 'yash', 'yash@gmai.com', 'Active', '2025-04-13 12:47:49', '2025-04-13 12:47:49'),
(4, 'abc', 'abc@gmail.com', 'Active', '2025-04-13 13:39:09', '2025-04-13 13:39:09'),
(5, 'mike', 'Mike@mike.com', 'Active', '2025-04-13 13:39:19', '2025-04-13 13:39:19'),
(6, 'pixel', 'pixel@pixel.com', 'Active', '2025-04-13 13:51:10', '2025-04-13 13:51:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `round_robin_users`
--
ALTER TABLE `round_robin_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_round_robin_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `round_robin_users`
--
ALTER TABLE `round_robin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `round_robin_users`
--
ALTER TABLE `round_robin_users`
  ADD CONSTRAINT `fk_round_robin_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
