-- phpMyAdmin SQL Dump
-- version 3.4.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Sep 2011 um 17:53
-- Server Version: 5.1.53
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `uploader`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ul_reports`
--

DROP TABLE IF EXISTS `ul_reports`;
CREATE TABLE IF NOT EXISTS `ul_reports` (
  `id` char(32) NOT NULL,
  `visibility` enum('public','private') NOT NULL,
  `creation` int(11) NOT NULL,
  `report` blob NOT NULL,
  `size` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `visibility` (`visibility`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `ul_stats`
--
DROP VIEW IF EXISTS `ul_stats`;
CREATE TABLE IF NOT EXISTS `ul_stats` (
`creation` int(11)
,`count` bigint(21)
);
-- --------------------------------------------------------

--
-- Struktur des Views `ul_stats`
--
DROP TABLE IF EXISTS `ul_stats`;

CREATE VIEW `ul_stats` AS select `ul_reports`.`creation` AS `creation`,count(`ul_reports`.`id`) AS `count` from `ul_reports` group by `ul_reports`.`creation`;
