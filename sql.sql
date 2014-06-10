-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 06, 2014 at 07:42 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `MobGame`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE IF NOT EXISTS `cars` (
  `CA_id` int(11) NOT NULL AUTO_INCREMENT,
  `CA_name` varchar(255) NOT NULL,
  `CA_value` int(11) NOT NULL,
  `CA_theftChance` int(11) NOT NULL,
  PRIMARY KEY (`CA_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`CA_id`, `CA_name`, `CA_value`, `CA_theftChance`) VALUES
(1, 'Peugeot 106', 400, 333),
(2, 'Citroen Saxo', 500, 333),
(3, 'Ford Fiesta', 600, 333),
(4, 'VW Golf', 1500, 250),
(5, 'Audi A3', 3200, 150),
(6, 'BMW 5 Series', 16000, 75),
(7, 'Porsche 911', 45000, 15),
(8, 'Ferrari California', 90000, 5);

-- --------------------------------------------------------

--
-- Table structure for table `crimes`
--

CREATE TABLE IF NOT EXISTS `crimes` (
  `C_id` int(11) NOT NULL AUTO_INCREMENT,
  `C_name` varchar(120) NOT NULL,
  `C_cooldown` int(11) NOT NULL,
  `C_money` int(11) NOT NULL,
  `C_maxMoney` int(11) NOT NULL,
  `C_level` int(11) NOT NULL,
  PRIMARY KEY (`C_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`C_id`, `C_name`, `C_cooldown`, `C_money`, `C_maxMoney`, `C_level`) VALUES
(1, 'Mug an old lady', 20, 1, 5, 1),
(2, 'Rob a cab driver', 45, 10, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gameNews`
--

CREATE TABLE IF NOT EXISTS `gameNews` (
  `GN_id` int(11) NOT NULL AUTO_INCREMENT,
  `GN_author` int(11) NOT NULL,
  `GN_title` varchar(120) NOT NULL,
  `GN_text` text NOT NULL,
  `GN_date` int(11) NOT NULL,
  PRIMARY KEY (`GN_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `gameNews`
--

INSERT INTO `gameNews` (`GN_id`, `GN_author`, `GN_title`, `GN_text`, `GN_date`) VALUES
(1, 1, 'Some Title 1', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562841),
(2, 1, 'Some Title 2', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562801),
(3, 1, 'Some Title 3', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562741),
(4, 1, 'Some Title 4', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562701),
(5, 1, 'Some Title 5', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562641),
(6, 1, 'Some Title 6', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562601);

-- --------------------------------------------------------

--
-- Table structure for table `gangs`
--

CREATE TABLE IF NOT EXISTS `gangs` (
  `G_id` int(11) NOT NULL AUTO_INCREMENT,
  `G_name` varchar(120) NOT NULL,
  `G_desc` text NOT NULL,
  PRIMARY KEY (`G_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `gangs`
--

INSERT INTO `gangs` (`G_id`, `G_name`, `G_desc`) VALUES
(0, 'None', 'This user is not in a gang!');

-- --------------------------------------------------------

--
-- Table structure for table `garage`
--

CREATE TABLE IF NOT EXISTS `garage` (
  `GA_id` int(11) NOT NULL AUTO_INCREMENT,
  `GA_uid` int(11) NOT NULL,
  `GA_car` int(11) NOT NULL,
  `GA_damage` int(11) NOT NULL,
  `GA_location` int(11) NOT NULL,
  PRIMARY KEY (`GA_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `garage`
--

INSERT INTO `garage` (`GA_id`, `GA_uid`, `GA_car`, `GA_damage`, `GA_location`) VALUES
(23, 1, 4, 0, 3),
(22, 3, 4, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `L_id` int(11) NOT NULL AUTO_INCREMENT,
  `L_name` varchar(120) NOT NULL,
  `L_cost` int(11) NOT NULL,
  `L_bullets` int(11) NOT NULL,
  `L_bulletCost` int(11) NOT NULL DEFAULT '100',
  `L_cooldown` int(11) NOT NULL,
  PRIMARY KEY (`L_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`L_id`, `L_name`, `L_cost`, `L_bullets`, `L_bulletCost`, `L_cooldown`) VALUES
(1, 'London', 170, 11977, 100, 3600),
(3, 'Rome', 220, 0, 100, 4800),
(2, 'Paris', 200, 0, 100, 4200);

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `M_id` int(11) NOT NULL AUTO_INCREMENT,
  `M_uid` int(11) NOT NULL,
  `M_sid` int(11) NOT NULL,
  `M_subject` varchar(120) NOT NULL,
  `M_text` text NOT NULL,
  `M_type` int(11) NOT NULL,
  `M_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`M_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mail`
--

INSERT INTO `mail` (`M_id`, `M_uid`, `M_sid`, `M_subject`, `M_text`, `M_type`, `M_read`) VALUES
(1, 1, 2, 'cvdfdsfds', 'dsfdfadasfdasfad', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `N_id` int(11) NOT NULL AUTO_INCREMENT,
  `N_uid` int(11) NOT NULL,
  `N_text` text NOT NULL,
  `N_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`N_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`N_id`, `N_uid`, `N_text`, `N_read`) VALUES
(1, 1, 'dsfdsasdsadsds', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
  `R_id` int(11) NOT NULL AUTO_INCREMENT,
  `R_name` varchar(100) NOT NULL,
  `R_exp` int(11) NOT NULL,
  `R_limit` int(11) NOT NULL,
  `R_cashReward` int(11) NOT NULL,
  `R_bulletReward` int(11) NOT NULL,
  PRIMARY KEY (`R_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`R_id`, `R_name`, `R_exp`, `R_limit`, `R_cashReward`, `R_bulletReward`) VALUES
(1, 'Lowlife', 20, 0, 75, 25),
(2, 'Thug', 50, 0, 150, 60),
(3, 'Criminal', 100, 0, 250, 100);

-- --------------------------------------------------------

--
-- Table structure for table `theft`
--

CREATE TABLE IF NOT EXISTS `theft` (
  `T_id` int(11) NOT NULL AUTO_INCREMENT,
  `T_name` varchar(255) NOT NULL,
  `T_chance` int(11) NOT NULL,
  `T_maxDamage` int(11) NOT NULL,
  `T_worstCar` int(11) NOT NULL,
  `T_bestCar` int(11) NOT NULL,
  PRIMARY KEY (`T_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `theft`
--

INSERT INTO `theft` (`T_id`, `T_name`, `T_chance`, `T_maxDamage`, `T_worstCar`, `T_bestCar`) VALUES
(1, 'Steal from street corner', 50, 100, 1, 3),
(2, 'Steel from 24hour car park', 35, 75, 1, 3),
(3, 'Steal from private car park', 25, 60, 1, 4),
(4, 'Steal from golf course', 18, 30, 3, 6),
(5, 'Steal from car dearlership', 10, 10, 4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `U_id` int(11) NOT NULL AUTO_INCREMENT,
  `U_name` varchar(30) NOT NULL,
  `U_email` varchar(100) NOT NULL,
  `U_password` varchar(255) NOT NULL,
  `U_userLevel` int(1) NOT NULL,
  `U_status` int(1) NOT NULL,
  PRIMARY KEY (`U_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`U_id`, `U_name`, `U_email`, `U_password`, `U_userLevel`, `U_status`) VALUES
(6, 'Admin', 'Admin@yourgame.com', '0f4afdf3a12e95916d9750debbcff3999a502aa9', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userStats`
--

CREATE TABLE IF NOT EXISTS `userStats` (
  `US_id` int(11) NOT NULL,
  `US_health` int(11) NOT NULL DEFAULT '100',
  `US_exp` int(11) NOT NULL DEFAULT '0',
  `US_money` int(11) NOT NULL DEFAULT '250',
  `US_bullets` int(11) NOT NULL DEFAULT '100',
  `US_backfire` int(11) NOT NULL DEFAULT '50',
  `US_credits` int(11) NOT NULL DEFAULT '0',
  `US_weapon` int(11) NOT NULL DEFAULT '1',
  `US_rank` int(11) NOT NULL DEFAULT '1',
  `US_gang` int(11) NOT NULL DEFAULT '0',
  `US_location` int(11) NOT NULL DEFAULT '1',
  `US_crimes` varchar(255) NOT NULL DEFAULT '35-25-15-5-5-5-5-5-5-5-5-5-5-5-5',
  `US_crimeTimer` int(11) NOT NULL DEFAULT '0',
  `US_jailTimer` int(11) NOT NULL DEFAULT '0',
  `US_theftTimer` int(11) NOT NULL DEFAULT '0',
  `US_chaseTimer` int(11) NOT NULL DEFAULT '0',
  `US_bulletTimer` int(11) NOT NULL DEFAULT '0',
  `US_travelTimer` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userStats`
--

INSERT INTO `userStats` (`US_id`, `US_health`, `US_exp`, `US_money`, `US_bullets`, `US_backfire`, `US_credits`, `US_weapon`, `US_rank`, `US_gang`, `US_location`, `US_crimes`, `US_crimeTimer`, `US_jailTimer`, `US_theftTimer`, `US_chaseTimer`, `US_bulletTimer`, `US_travelTimer`) VALUES
(6, 100, 0, 250, 100, 50, 0, 1, 1, 0, 1, '35-25-15-5-5-5-5-5-5-5-5-5-5-5-5', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `userTimers`
--

CREATE TABLE IF NOT EXISTS `userTimers` (
  `UT_user` int(11) NOT NULL,
  `UT_desc` varchar(32) NOT NULL,
  `UT_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `weapons`
--

CREATE TABLE IF NOT EXISTS `weapons` (
  `W_id` int(11) NOT NULL AUTO_INCREMENT,
  `W_name` varchar(100) NOT NULL,
  `W_accuracy` int(11) NOT NULL,
  PRIMARY KEY (`W_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`W_id`, `W_name`, `W_accuracy`) VALUES
(1, 'Pistol', 5);
