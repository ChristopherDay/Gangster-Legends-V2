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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `gangs`
--

CREATE TABLE IF NOT EXISTS `gangs` (
  `G_id` int(11) NOT NULL AUTO_INCREMENT,
  `G_name` varchar(120) NOT NULL,
  `G_bank` int(11) NOT NULL DEFAULT 0,
  `G_desc` text NOT NULL DEFAULT '',
  `G_level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`G_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Dumping data for table `gangs`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `M_id` int(11) NOT NULL AUTO_INCREMENT,
  `M_time` int(11) NOT NULL,
  `M_uid` int(11) NOT NULL,
  `M_sid` int(11) NOT NULL,
  `M_subject` varchar(120) NOT NULL,
  `M_parent` int(11) NOT NULL,
  `M_text` text NOT NULL,
  `M_type` int(11) NOT NULL,
  `M_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`M_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `N_id` int(11) NOT NULL AUTO_INCREMENT,
  `N_uid` int(11) NOT NULL,
  `N_time` int(11) NOT NULL,
  `N_text` text NOT NULL,
  `N_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`N_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `theft`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `S_id` int(11) NOT NULL AUTO_INCREMENT,
  `S_desc` varchar(255) NOT NULL,
  `S_value` text NOT NULL,
  PRIMARY KEY (`S_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Dumping data for table `theft`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `userStats`
--

CREATE TABLE IF NOT EXISTS `userStats` (
  `US_id` int(11) NOT NULL,
  `US_health` int(11) NOT NULL DEFAULT '100',
  `US_exp` int(11) NOT NULL DEFAULT '0',
  `US_money` int(11) NOT NULL DEFAULT '250',
  `US_bank` int(11) NOT NULL DEFAULT '0',
  `US_bullets` int(11) NOT NULL DEFAULT '100',
  `US_backfire` int(11) NOT NULL DEFAULT '50',
  `US_credits` int(11) NOT NULL DEFAULT '0',
  `US_pic` varchar(200) NOT NULL DEFAULT 'themes/default/images/default-profile-picture.png',
  `US_bio` varchar(1000) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userStats`

-- --------------------------------------------------------

--
-- Table structure for table `userTimers`
--

CREATE TABLE IF NOT EXISTS `userTimers` (
  `UT_user` int(11) NOT NULL,
  `UT_desc` varchar(32) NOT NULL,
  `UT_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `weapons`
--

CREATE TABLE IF NOT EXISTS `weapons` (
  `W_id` int(11) NOT NULL AUTO_INCREMENT,
  `W_name` varchar(100) NOT NULL,
  `W_accuracy` int(11) NOT NULL,
  PRIMARY KEY (`W_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
