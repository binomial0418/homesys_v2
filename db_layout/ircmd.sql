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
-- 資料表結構 `ircmd`
--

CREATE TABLE `ircmd` (
  `cmd_no` bigint(20) UNSIGNED NOT NULL,
  `ir_no` varchar(30) NOT NULL,
  `cmd` varchar(20) NOT NULL,
  `eff_tim` datetime NOT NULL DEFAULT current_timestamp(),
  `tsc` varchar(1) NOT NULL,
  `rtt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `ircmd`
--
ALTER TABLE `ircmd`
  ADD PRIMARY KEY (`cmd_no`),
  ADD UNIQUE KEY `cmd_no` (`cmd_no`),
  ADD KEY `ir_no` (`ir_no`,`rtt`),
  ADD KEY `ir_no_2` (`ir_no`,`eff_tim`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `ircmd`
--
ALTER TABLE `ircmd`
  MODIFY `cmd_no` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
