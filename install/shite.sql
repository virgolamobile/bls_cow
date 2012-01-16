-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 16, 2012 at 11:48 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shite`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(9) NOT NULL auto_increment,
  `parent` int(9) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent`, `name`) VALUES
(1, 0, 'wear'),
(2, 0, 'house'),
(3, 2, '1-room'),
(4, 2, '2-room'),
(5, 2, '3-room'),
(6, 2, 'manor');

-- --------------------------------------------------------

--
-- Table structure for table `category_lang`
--

CREATE TABLE IF NOT EXISTS `category_lang` (
  `id` int(11) NOT NULL auto_increment,
  `lang` varchar(2) NOT NULL,
  `text` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `category_lang`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('d3e13e041c9017d21cbb1043abe5eca9', '192.168.255.152', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1326741651, ''),
('bd1cb55b11b164318ae17c4b1e40eeb0', '192.168.255.152', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0', 1326740080, '');

-- --------------------------------------------------------

--
-- Table structure for table `filter`
--

CREATE TABLE IF NOT EXISTS `filter` (
  `id` int(9) NOT NULL auto_increment,
  `type` int(9) NOT NULL,
  `name` varchar(50) NOT NULL,
  `min` int(10) default NULL,
  `max` int(10) default NULL,
  `default` varchar(50) default NULL,
  `format` varchar(50) NOT NULL,
  `cast` varchar(50) NOT NULL,
  `order` smallint(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `filter`
--

INSERT INTO `filter` (`id`, `type`, `name`, `min`, `max`, `default`, `format`, `cast`, `order`) VALUES
(1, 2, 'color', NULL, NULL, NULL, 'select', 'string', 0),
(2, 2, 'brand', NULL, NULL, NULL, 'text', 'string', 0),
(3, 2, 'year', 1900, 2012, NULL, 'range', 'integer', 0),
(4, 3, 'size', 0, 5000, '75', 'range', 'integer', 0),
(5, 2, 'typology', NULL, NULL, NULL, 'checkbox', 'option', 0);

-- --------------------------------------------------------

--
-- Table structure for table `filter_lang`
--

CREATE TABLE IF NOT EXISTS `filter_lang` (
  `filter` int(9) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `label` varchar(50) default NULL,
  PRIMARY KEY  (`filter`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filter_lang`
--

INSERT INTO `filter_lang` (`filter`, `lang`, `label`) VALUES
(1, 'it', 'Colore'),
(2, 'it', 'Marca'),
(3, 'it', 'Anno di immatricolazione'),
(5, 'it', 'Tipologia');

-- --------------------------------------------------------

--
-- Table structure for table `filter_option`
--

CREATE TABLE IF NOT EXISTS `filter_option` (
  `id` int(9) NOT NULL auto_increment,
  `filter` int(9) NOT NULL,
  `order` smallint(2) NOT NULL,
  PRIMARY KEY  (`id`,`filter`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `filter_option`
--

INSERT INTO `filter_option` (`id`, `filter`, `order`) VALUES
(1, 1, 0),
(2, 1, 0),
(3, 1, 0),
(4, 1, 0),
(5, 1, 0),
(6, 1, 0),
(7, 1, 0),
(8, 1, 0),
(9, 5, 0),
(10, 5, 0),
(11, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `filter_option_lang`
--

CREATE TABLE IF NOT EXISTS `filter_option_lang` (
  `filter_option` int(9) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `label` varchar(255) default NULL,
  PRIMARY KEY  (`filter_option`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filter_option_lang`
--

INSERT INTO `filter_option_lang` (`filter_option`, `lang`, `label`) VALUES
(1, 'it', 'Rosso'),
(2, 'it', 'Arancione'),
(3, 'it', 'Giallo'),
(4, 'it', 'Verde'),
(5, 'it', 'Blu'),
(6, 'it', 'Viola'),
(7, 'it', 'Nero'),
(8, 'it', 'Bianco'),
(9, 'it', 'Nuovo'),
(10, 'it', 'Km0'),
(11, 'it', 'Usato');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(9) NOT NULL auto_increment,
  `type` int(9) NOT NULL default '0',
  `status` tinyint(1) NOT NULL,
  `activation` varchar(32) default NULL,
  `user` int(9) NOT NULL,
  `creation` varchar(10) NOT NULL,
  `last_modify` varchar(10) NOT NULL,
  `last_view` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `type`, `status`, `activation`, `user`, `creation`, `last_modify`, `last_view`) VALUES
(1, 2, 1, NULL, 1, '1326210474', '', ''),
(2, 2, 1, NULL, 1, '1326210474', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE IF NOT EXISTS `item_category` (
  `item_id` int(9) NOT NULL,
  `category_id` int(9) NOT NULL,
  PRIMARY KEY  (`item_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_category`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_filter`
--

CREATE TABLE IF NOT EXISTS `item_filter` (
  `item` int(9) NOT NULL,
  `filter` int(9) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`item`,`filter`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_filter`
--

INSERT INTO `item_filter` (`item`, `filter`, `value`) VALUES
(1, 1, '4'),
(1, 2, 'Vespa'),
(2, 3, '2000'),
(2, 1, '4'),
(1, 3, '1993'),
(2, 2, 'Vespa'),
(1, 5, '9'),
(2, 5, '10');

-- --------------------------------------------------------

--
-- Table structure for table `item_lang`
--

CREATE TABLE IF NOT EXISTS `item_lang` (
  `item` int(9) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` varchar(30) NOT NULL,
  `summary` longtext NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY  (`item`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_lang`
--

INSERT INTO `item_lang` (`item`, `lang`, `title`, `summary`, `content`) VALUES
(1, 'it', 'Vespa 128', 'Lorem ipsum dolor sit amet.', 'The quick brown fox jumps over the lazy dog.'),
(2, 'it', 'Vespa 64', 'Lorem ipsum dolor sit amet.', 'The quick brown fox jumps over the lazy dog.');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `name`) VALUES
(1, 'car'),
(2, 'moto'),
(3, 'house');

-- --------------------------------------------------------

--
-- Table structure for table `type_lang`
--

CREATE TABLE IF NOT EXISTS `type_lang` (
  `type` int(9) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `label` varchar(50) default NULL,
  PRIMARY KEY  (`type`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `type_lang`
--

INSERT INTO `type_lang` (`type`, `lang`, `label`) VALUES
(1, 'it', 'Auto'),
(2, 'it', 'Motocicletta'),
(3, 'it', 'Casa');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(7) NOT NULL auto_increment,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `activation` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `status`, `activation`) VALUES
(1, 'test@localhost.com', '67f2bd3ec523bc7ade1c28082e7615fb', '12345', 1, '5ca439a3a2faf93e5b49564f5c6e5f07');
