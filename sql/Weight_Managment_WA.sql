-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 20, 2024 at 01:57 PM
-- Server version: 10.3.39-MariaDB-cll-lve
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uf8863a_Weight_Managment_WA`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `user_id` int(11) NOT NULL,
  `milestone_alert` tinyint(1) NOT NULL DEFAULT 1,
  `reminder` int(11) NOT NULL DEFAULT 1,
  `weight_fluctuation` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `use_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `height` float NOT NULL,
  `target_weight` float DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `health_Condition` enum('Diabetic',' Pre_Diabetic') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weight_recorded`
--

CREATE TABLE `weight_recorded` (
  `weigh_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `weight` float NOT NULL,
  `date_time` datetime NOT NULL,
  `bodyMass_percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD KEY `user_preferences_user_id_user_tbl_use_id` (`user_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`use_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indexes for table `weight_recorded`
--
ALTER TABLE `weight_recorded`
  ADD PRIMARY KEY (`weigh_id`),
  ADD KEY `weight_recorded_user_id_user_tbl_use_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `use_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weight_recorded`
--
ALTER TABLE `weight_recorded`
  MODIFY `weigh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_user_id_user_tbl_use_id` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`use_id`);

--
-- Constraints for table `weight_recorded`
--
ALTER TABLE `weight_recorded`
  ADD CONSTRAINT `weight_recorded_user_id_user_tbl_use_id` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`use_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
