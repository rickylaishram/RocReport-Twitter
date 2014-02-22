-- phpMyAdmin SQL Dump
-- version 4.0.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2014 at 09:11 PM
-- Server version: 5.5.31-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_rocrep`
--

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `email` text NOT NULL,
  `picture` text NOT NULL,
  `created` int(15) NOT NULL,
  `loc_coord` varchar(100) NOT NULL,
  `loc_name` varchar(100) NOT NULL,
  `re_status` int(2) NOT NULL DEFAULT '1',
  `votes` int(20) NOT NULL,
  `votedby` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_update`
--

CREATE TABLE IF NOT EXISTS `report_update` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_report` int(10) NOT NULL,
  `details` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwordin` varchar(200) NOT NULL,
  `joinedon` bigint(20) NOT NULL,
  `loggedon` bigint(20) NOT NULL,
  `iplogged` varchar(40) NOT NULL,
  `ipjoined` varchar(40) NOT NULL,
  `fbid` bigint(25) NOT NULL,
  `level` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
