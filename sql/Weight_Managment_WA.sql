-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 05, 2025 at 08:38 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dissertation`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

DROP TABLE IF EXISTS `user_preferences`;
CREATE TABLE IF NOT EXISTS `user_preferences` (
  `user_id` int NOT NULL,
  `milestone_alert` tinyint(1) NOT NULL DEFAULT '1',
  `inactivity_reminder` int NOT NULL DEFAULT '1',
  `weight_fluctuation` decimal(10,0) NOT NULL,
  KEY `user_preferences_user_id_user_tbl_use_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

DROP TABLE IF EXISTS `user_tbl`;
CREATE TABLE IF NOT EXISTS `user_tbl` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `age` int NOT NULL,
  `target_weight` decimal(5,2) DEFAULT NULL,
  `health_Condition` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'Prediabetes',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `index_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `weight_recorded`
--

DROP TABLE IF EXISTS `weight_recorded`;
CREATE TABLE IF NOT EXISTS `weight_recorded` (
  `weight_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `date_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `bodyMass_percentage` float NOT NULL,
  PRIMARY KEY (`weight_id`),
  KEY `weight_recorded_user_id_user_tbl_use_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1339 DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `weight_recorded`
--
ALTER TABLE `weight_recorded`
  ADD CONSTRAINT `weight_recorded_user_id_user_tbl_use_id` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
