-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 23, 2011 at 06:22 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `ajde-samples`
--

-- --------------------------------------------------------

--
-- Table structure for table `acl`
--

CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object` int(10) unsigned NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object` (`object`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `acl`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` text,
  `usergroup` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`),
  KEY `usergroup` (`usergroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `usergroup`, `email`, `fullname`) VALUES
(29, 'ajde', 'ajde', 2, '', 'Ajde Test account'),
(30, 'admin', 'admin', 1, '', 'Ajde Admin account');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`id`, `name`) VALUES
(1, 'admins'),
(2, 'users');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`usergroup`) REFERENCES `usergroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
