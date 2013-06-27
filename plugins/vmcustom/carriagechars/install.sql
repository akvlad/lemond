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
-- Структура таблицы `lem__virtuemart_product_custom_plg_carriagechars`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_custom_plg_carriagechars` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(5) unsigned NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT '',
  `age` varchar(10) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `gender` varchar(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
