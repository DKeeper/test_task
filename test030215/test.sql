-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 04 2015 г., 06:00
-- Версия сервера: 5.5.34-0ubuntu0.13.10.1
-- Версия PHP: 5.5.14-1+deb.sury.org~saucy+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `email` text,
  `phone` text,
  `first_name` text,
  `last_name` text,
  `avatar` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `email`, `phone`, `first_name`, `last_name`, `avatar`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@test.th', '+71112223344', 'Test', 'Test', NULL),
(2, 'test_reg', '44f278b83876c0dc5da692b944be0feb', 'ddd@dd.dd', '+1234567890', 'Dmitry', 'Kapenkin', '/test030215/upload/sade.jpg'),
(3, 'testss', 'd58e3582afa99040e27b92b13c8f2280', 'sdf@sds.sss', '+1234567890', 's', 's', '/test030215/upload/20150204_172440.jpg'),
(4, 'test_reg1', 'c73ef0493e37413babe6874378683ec8', 'dkaaa@ss.rr', '+1234567890', 'asasda', 'asdasdass', '/test030215/upload/JylzyG02v88.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
