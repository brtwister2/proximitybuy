-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 23/01/2017 às 15:47
-- Versão do servidor: 5.5.41-0ubuntu0.14.04.1
-- Versão do PHP: 5.5.9-1ubuntu4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `proximitybuy`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `application`
--

CREATE TABLE IF NOT EXISTS `application` (
  `appid` varchar(255) NOT NULL,
  `trackid` varchar(255) DEFAULT NULL,
  `platform` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '1',
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Fazendo dump de dados para tabela `application`
--

INSERT INTO `application` (`appid`, `trackid`, `platform`, `category`, `id`, `status`, `icon`) VALUES
('br.brunovercosa.metrobh', '29AFD8DF-C416-4D41-8D49-36C27157E65A', 1, 1, 5, 1, '//lh6.ggpht.com/nQEnrvkmjY1XINtoDDhoMQGcM6yAUr0TSrd5igC0R1GBJIi2q47jdwqU_6oWIp8AiVw=w300'),
('1057649491', 'B73F2D8B-DD2D-449C-A554-5F6D49293849', 2, 1, 8, 1, 'http://is1.mzstatic.com/image/thumb/Purple1/v4/b4/ac/a0/b4aca062-c26d-e5f3-33e6-c6487bf503dd/source/512x512bb.jpg'),
('529479190', '304E4DFF-F44B-4AA5-B6AA-D29A7CD7A2D2', 2, 2, 9, 1, 'http://is2.mzstatic.com/image/thumb/Purple4/v4/7b/9c/e1/7b9ce127-224a-9e2b-9ff9-aed36be9a64e/source/512x512bb.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign`
--

CREATE TABLE IF NOT EXISTS `campaign` (
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `title` varchar(150) NOT NULL,
  `bid` varchar(255) NOT NULL,
  `budget` double NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Fazendo dump de dados para tabela `campaign`
--

INSERT INTO `campaign` (`views`, `created`, `link`, `img`, `title`, `bid`, `budget`, `status`, `id`, `name`, `description`) VALUES
(0, '2016-05-22 17:28:51', 'https://shopify.com', 'http://boomsoft.com.br/proximitybuy/api/imgs/5741ec53e58d2.png', 'Hello you', '109', 0, 1, 13, 'Calvin Klain Men Jacket', 'What else could you ask for? Only $20'),
(0, '2016-05-22 17:34:44', 'https://shopify.com', 'http://boomsoft.com.br/proximitybuy/api/imgs/5741edb4b7848.png', 'Calvin Klein Jacket', '12', 0, 1, 14, 'Calvin Klain Women Jacket', 'What else could you ask for? Only $20'),
(0, '2016-05-22 20:59:02', 'http://www.shoppify.com', 'http://boomsoft.com.br/proximitybuy/api/imgs/57421d96baa5c.jpeg', 'title', 'a219156e-de43-4cc6-bfdb-cb330a4704cb', 1, 1, 21, 'My Campaign', 'desc');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
