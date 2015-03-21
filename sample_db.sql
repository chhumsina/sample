-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2015 at 12:17 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sample_db`
--
CREATE DATABASE IF NOT EXISTS `sample_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `sample_db`;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_roles`
--

CREATE TABLE IF NOT EXISTS `assigned_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `title`) VALUES
(1, 'Phnom Penh'),
(2, 'Siem Reap'),
(3, 'Takeo'),
(4, 'Kandal');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `use_type` int(11) NOT NULL,
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=60 ;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `username`, `email`, `password`, `remember_token`, `first_name`, `last_name`, `location`, `phone`, `address`, `photo`, `status`, `use_type`, `confirmation_code`, `created_at`, `updated_at`) VALUES
(1, 'sina', 'sina@gmail.com', '$2y$10$e4EHPhPaTDc8SeIUlzijLuNN3vsISwyMAfjWIIPj1bUDb3g0Wh2Ey', 'D0KaLX5Rg8LUZL2yNBGcMqpQRRfFAOgpRFpWGKfTIkMp54fzP2fI9fjntSco', 'Mikel', 'Grimes', 'voluptatem', '(973)334-3909', '2456 Lowe Isle\nSouth Madelynnland, MN 18133-9270', 'pariatur', 1, 1, '', '2015-03-14 10:08:43', '2015-03-15 07:51:45'),
(2, 'runolfsdottir.constance', 'vondricka@mitchell.com', 'aliquid', '', 'Manley', 'Kilback', 'facere', '293.962.7440', '031 Harvey Ville Apt. 038\nBrakusmouth, MS 19672', 'omnis', 0, 2, '', '2015-03-14 10:08:43', '2015-03-14 10:08:43'),
(3, 'cartwright.alberto', 'stoltenberg.sabryna@hyattondricka.com', 'aspernatur', '', 'Kaycee', 'Nikolaus', 'aut', '1-116-690-8799x87860', '7049 Reichel Stream\nAbelardoburgh, UT 63012-9758', 'earum', 0, 2, '', '2015-03-14 10:08:43', '2015-03-14 10:08:43'),
(4, 'hackett.angelita', 'wdenesik@baumbach.biz', 'placeat', '', 'Cristopher', 'Rath', 'non', '1-875-219-4392x723', '342 Jamil Prairie\nPort Samsonfurt, OH 71411', 'earum', 0, 2, '', '2015-03-14 10:08:43', '2015-03-14 10:08:43'),
(5, 'joanne.wiza', 'dach.dell@hartmann.com', 'sapiente', '', 'Pattie', 'Willms', 'quo', '200-423-7827x92974', '16216 Zulauf Square\nWillmsborough, NE 99246-6836', 'expedita', 0, 2, '', '2015-03-14 10:08:43', '2015-03-14 10:08:43'),
(6, 'paucek.elmo', 'kaylee47@pricealtenwerth.biz', 'tempora', '', 'Horace', 'Volkman', 'et', '(156)096-3941x083', '8405 Nicolas Union\nNew Rashawnton, WA 43425-0521', 'nobis', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(7, 'durgan.felicita', 'name09@hotmail.com', 'suscipit', '', 'Winifred', 'Mitchell', 'autem', '(183)205-2246', '507 Glenda Fall Apt. 614\nNew Luellahaven, NE 37212', 'tempora', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(8, 'rrutherford', 'gutmann.priscilla@hotmail.com', 'id', '', 'Angel', 'Hagenes', 'quos', '(642)255-5754', '27253 Newell Crossroad\nArchstad, SC 09506-1276', 'autem', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(9, 'deondre52', 'ccrist@windler.org', 'odio', '', 'Marjory', 'Goldner', 'vitae', '(760)882-5413', '1909 Sebastian Cliffs\nHaileyville, MI 39742-7731', 'natus', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(10, '', 'flavie.johnston@yahoo.com', 'eligendi', '', 'Fidel', 'Emmerich', 'et', '427-706-2304x659', '2710 Era Mews Suite 066Aufderharside, HI 74914', 'quia', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 15:45:12'),
(11, 'sandy.schuster', 'nathan30@beier.net', 'eum', '', 'Salvador', 'Bosco', 'perferendis', '370.714.2099', '6243 Harvey Heights\nWizaberg, NJ 07942-5418', 'ea', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(12, 'marjory.volkman', 'crystal44@gmail.com', 'sapiente', '', 'Mac', 'Bartell', 'odit', '1-728-443-5648x247', '6566 Feil Way Suite 722\nEnatown, IN 65733', 'maiores', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(13, 'effertz.doris', 'grimes.krista@rosenbaumchamplin.com', 'repellendus', '', 'Russell', 'Runolfsdottir', 'possimus', '359.088.1073', '503 Hansen Summit Apt. 329\nWilfredoshire, MD 54860-7942', 'asperiores', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(14, 'imani20', 'zion.fahey@stokes.com', 'et', '', 'Erik', 'Torphy', 'assumenda', '(248)738-6306x15755', '4840 Zieme Ports\nLehnerbury, OR 12917-3914', 'ut', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(15, 'sreynouch', 'sreynouch@gmail.com', 'autem', '', 'sreynouch', 'tesing', 'pp', '(011)146-0899x0191111', 'PP1115551', 'a', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 15:49:22'),
(16, 'kutch.lou', 'xschaden@yahoo.com', 'id', '', 'Watson', 'Haag', 'doloremque', '(775)097-1964x90411', '20568 Morissette Port Apt. 886\nWest Mateo, SD 20772', 'facilis', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(17, 'joannie.bins', 'pnienow@hotmail.com', 'est', '', 'Frederick', 'Zemlak', 'aperiam', '(442)343-8688x7541', '3748 Donavon Underpass\nCarolinashire, AK 05588', 'molestias', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(18, 'monty82', 'rgoyette@yahoo.com', 'corporis', '', 'Chance', 'Carter', 'laboriosam', '04047141317', '000 Fiona Plains\nEast Jeremyview, CO 06590', 'magni', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(19, 'idella22', 'margarett56@yahoo.com', 'laborum', '', 'Erin', 'Luettgen', 'expedita', '938.260.0756x8772', '6783 Olson Valley\nHayeschester, MI 91121-9883', 'aut', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(20, 'randy75', 'madison52@yahoo.com', 'et', '', 'Austyn', 'Sauer', 'cum', '1-164-364-0187', '9780 Schultz Overpass\nDinamouth, MA 40237-8361', 'neque', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(21, 'nnolan', 'watsica.lauryn@hotmail.com', 'et', '', 'Kavon', 'Moore', 'ut', '445.440.6966x471', '4877 Fidel Port Suite 278\nNorth Michelleport, NE 53379-5827', 'exercitationem', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(22, 'rocky.conroy', 'tevin77@gmail.com', 'illum', '', 'Aletha', 'Jakubowski', 'et', '185-221-7331x354', '0082 Lurline Cliff\nMaybellland, MI 05885', 'sunt', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(23, 'leon.ferry', 'germaine.hirthe@yahoo.com', 'totam', '', 'Violet', 'Stiedemann', 'expedita', '(830)685-4730x1207', '650 Gus Stream\nHaleighmouth, OK 04702-0949', 'autem', 0, 2, '', '2015-03-14 10:08:44', '2015-03-14 10:08:44'),
(24, 'jaron.wunsch', 'isabel.abshire@hotmail.com', 'aut', '', 'Elisha', 'Harris', 'voluptatem', '(091)161-1248x67416', '97585 Valerie Wall\nPort Grayson, AK 88037', 'id', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(25, 'josue35', 'keara57@hotmail.com', 'nisi', '', 'Eveline', 'West', 'et', '277-691-7444', '5245 Torey Garden Apt. 689\nNadiaview, IN 84084', 'incidunt', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(26, 'ccollins', 'howell.craig@yahoo.com', 'animi', '', 'Chasity', 'Wuckert', 'ut', '1-609-358-0501', '89626 Mann Forks Apt. 011\nLindland, AK 16404', 'reiciendis', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(27, 'paucek.braulio', 'brown.runte@yahoo.com', 'tenetur', '', 'Marisa', 'Herman', 'ea', '475.846.5626x297', '425 Kuhn Cape\nDeclanfort, TX 63433-4878', 'unde', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(28, 'oblock', 'morris.kozey@bechtelar.biz', 'molestias', '', 'Joshua', 'Jaskolski', 'quidem', '122.413.8985', '38286 Hilll Harbors Apt. 345\nBoganfort, ID 97624', 'quo', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(29, 'vkonopelski', 'ewell.gleason@yahoo.com', 'officia', '', 'Noble', 'Langosh', 'optio', '510.402.4313x72537', '17289 Chaya Road\nNorth Makenzie, FL 03162-1322', 'perspiciatis', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(30, 'julianne94', 'dare.remington@hotmail.com', 'iste', '', 'Fritz', 'Ullrich', 'deserunt', '03759141194', '6047 Huel Pike\nPagacport, IL 87648-6345', 'aut', 0, 2, '', '2015-03-14 10:08:45', '2015-03-14 10:08:45'),
(31, 'lovely', 'lovely@gmail.com', 'sinasina', '', '', '', '', '', '', '', 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'noya', 'noya@gmail.com', '@password@', '', '', '', '', '', '', '', 0, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'sinasina', 'sinasina@gmail.com', '$2y$10$z5whQ3tclcIm/EHy6LQhbuzZNURJWVH0fEU01/1myFMA/32QThQju', '', '', '', '', '', '', '', 0, 0, '', '2015-03-15 04:24:13', '2015-03-15 04:24:13'),
(34, 'lolo', 'lolo@lo.com', '$2y$10$34DjHo910N1j2LJmltT8IO66E06Y2bgUA2XmmAeZX5JX9y4Mmgg6K', '', '', '', '', '', '', '', 0, 0, '', '2015-03-15 04:37:43', '2015-03-15 04:37:43'),
(35, 'best', 'best@gmail.com', '$2y$10$Pag518b1cH8GsyZFHIu0quRsJVastLpZWcr2hOQs0NRt4jwd0lPjK', '', '', '', '', '', '', '', 1, 0, '', '2015-03-15 04:45:53', '2015-03-15 04:45:53'),
(36, 'kaka', 'kaka@gmail.com', '$2y$10$35f7tM3019Pc1bDan07aPefSFyFAo2oolun/xWMNE3KebKlZGPvGa', '', '', '', '', '', '', '', 0, 0, '', '2015-03-15 07:48:35', '2015-03-15 07:48:35'),
(37, 'koko', 'koko@gmail.com', '$2y$10$e4EHPhPaTDc8SeIUlzijLuNN3vsISwyMAfjWIIPj1bUDb3g0Wh2Ey', 'ZnVl5LWZ6DHg9ktKesvLQARUR8olQmQAWXDvjbqTcFf5bUHY8txFSmhaM1Fw', '', '', '', '', '', '', 1, 1, '', '2015-03-15 07:52:57', '2015-03-15 08:08:04'),
(38, 'nono', 'nono@no.com', '$2y$10$v8L689SDmC626UoJk.l6mOZlSiTE07wtFmNTsiBw2UpANyIkjKLCC', '5N11M0wQDhEMKVynARFY1ttei82HPu1iXK58S3hRDP2U5YWwUOfraVzsn5YD', '', '', '', '', '', '', 1, 1, '', '2015-03-15 08:16:52', '2015-03-15 08:17:39'),
(58, 'naja', 'chhumsina@gmail.com', '$2y$10$ElAPYoI8gaigVg0qFpLfOO7MO.0dkxQHv1VXJITKDr.Hqd.aj9bQu', '0srTBOazaBBZnOWnLSxC9tPxmYxVISiIxGZ0fuduBmWhQWwFrct1jQMliEzx', '', '', '', '', '', '', 1, 2, '', '2015-03-15 10:11:24', '2015-03-16 15:38:51'),
(59, 'nora', 'sinachhum.cist@gmail.com', '$2y$10$ILFAtbDGhtFqdzXjAnZHfuCi4wsK4JhCEQPzYqaF6fnuiYKX2siUe', 'w7mbrmNP5nbN3hhopKJZVxKuttCP5B1WdDLBPljULo0i0kc3z2HmkLDeoWLH', 'chhum', 'sina', '3', '0909', 'good\r\n', 'sample.png', 1, 2, '', '2015-03-16 15:39:40', '2015-03-18 13:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2015_03_14_163026_create_member_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `kind_of` int(8) NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `bra_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
