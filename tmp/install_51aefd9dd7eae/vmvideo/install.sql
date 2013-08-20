-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 04 2013 г., 15:23
-- Версия сервера: 5.5.30
-- Версия PHP: 5.4.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `lemondi`
--

-- --------------------------------------------------------

--
-- Структура таблицы `lem__virtuemart_product_custom_plg_vmvideo`
--

CREATE TABLE IF NOT EXISTS `lem__virtuemart_product_custom_plg_vmvideo` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(5) unsigned NOT NULL,
  `virtuemart_custom_id` int(5) NOT NULL,
  `is_local` int(1) NOT NULL DEFAULT 0,
  `link` text NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
