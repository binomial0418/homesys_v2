-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2021 年 07 月 12 日 11:04
-- 伺服器版本： 10.3.24-MariaDB
-- PHP 版本： 7.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `duckegg`
--

-- --------------------------------------------------------

--
-- 資料表結構 `setval`
--

CREATE TABLE `setval` (
  `ser_no` bigint(20) UNSIGNED NOT NULL,
  `typ` varchar(50) NOT NULL,
  `val` varchar(100) NOT NULL,
  `cmt` varchar(500) DEFAULT NULL,
  `rtt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `setval`
--
ALTER TABLE `setval`
  ADD PRIMARY KEY (`ser_no`),
  ADD UNIQUE KEY `ser_no` (`ser_no`),
  ADD UNIQUE KEY `typ` (`typ`),
  ADD KEY `typ_2` (`typ`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `setval`
--
ALTER TABLE `setval`
  MODIFY `ser_no` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
