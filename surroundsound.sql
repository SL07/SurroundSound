-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2014 at 01:14 AM
-- Server version: 5.6.16
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `surroundsound`
--
CREATE DATABASE IF NOT EXISTS `surroundsound` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `surroundsound`;

-- --------------------------------------------------------

--
-- Table structure for table `current_playing`
--

CREATE TABLE IF NOT EXISTS `current_playing` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `track` varchar(20) NOT NULL,
  `artist` varchar(20) NOT NULL,
  `device_count` int(4) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `played_history`
--

CREATE TABLE IF NOT EXISTS `played_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `track` varchar(20) NOT NULL,
  `artist` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
