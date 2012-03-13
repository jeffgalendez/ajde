-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 09, 2011 at 11:44 AM
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

DROP TABLE IF EXISTS `acl`;
CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('user','usergroup') NOT NULL,
  `user` int(10) unsigned DEFAULT NULL,
  `usergroup` int(10) unsigned DEFAULT NULL,
  `module` varchar(255) NOT NULL DEFAULT '*',
  `action` varchar(255) NOT NULL DEFAULT '*',
  `permission` enum('allow','own','deny') NOT NULL DEFAULT 'own',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `usergroup` (`usergroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acl`
--
ALTER TABLE `acl`
  ADD CONSTRAINT `acl_ibfk_3` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `acl_ibfk_4` FOREIGN KEY (`usergroup`) REFERENCES `usergroup` (`id`);