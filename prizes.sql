-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 10, 2018 at 05:01 
-- Server version: 5.6.22-log
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `prizes`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_prize`
--

CREATE TABLE IF NOT EXISTS `category_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `category_prize`
--

INSERT INTO `category_prize` (`id`, `name`, `title`) VALUES
(1, 'score', 'Бонусные баллы'),
(2, 'money', 'Денежный'),
(3, 'gift', 'Случайный предмет');

-- --------------------------------------------------------

--
-- Table structure for table `prize`
--

CREATE TABLE IF NOT EXISTS `prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL COMMENT 'Field update in all rows during Insert or Delete',
  `title` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `is_limit` tinyint(1) NOT NULL DEFAULT '1',
  `id_category` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `amount` (`amount`),
  KEY `is_limit` (`is_limit`),
  KEY `id_category` (`id_category`),
  KEY `index` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `prize`
--

INSERT INTO `prize` (`id`, `order`, `title`, `amount`, `is_limit`, `id_category`) VALUES
(1, 1, 'Бонусные баллы', 0, 0, 1),
(2, 2, 'Денежный приз', 1000, 1, 2),
(3, 3, 'Мандарин', 1000, 1, 3),
(4, 4, 'Плюшевая свинка', 500, 1, 3),
(5, 5, 'Билет в кино', 100, 1, 3),
(6, 6, 'Сноуборд', 10, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`, `title`) VALUES
(1, 'interval_get_prize', '15', 'Интервал получения приза для пользователя (сек)'),
(2, 'money_max', '10000', 'Максимальная сумма выигрыша'),
(3, 'money_min', '100', 'Минимальная сумма выигрыша'),
(4, 'score_max', '10', 'Максимальное количество баллов'),
(5, 'score_min', '1', 'Минимальное количество баллов'),
(6, 'score_ratio', '1000', 'Коэффициент конвертации денег в баллы');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `key` varchar(64) NOT NULL,
  `is_activate` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` int(11) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL,
  `cart` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `token` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user2prize`
--

CREATE TABLE IF NOT EXISTS `user2prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_prize` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '-1 - отменён, 1 - ожидает, 2 - подтверждён, 5 - в обработке, 10 - исполнен',
  `count` int(11) NOT NULL DEFAULT '1',
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`,`id_prize`),
  KEY `amount` (`count`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
