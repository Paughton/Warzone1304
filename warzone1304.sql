-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2021 at 03:05 AM
-- Server version: 5.6.51
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warzone1304`
--

-- --------------------------------------------------------

--
-- Table structure for table `alliance`
--

CREATE TABLE `alliance` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `president` varchar(20) NOT NULL,
  `flag` varchar(20) NOT NULL,
  `gold` bigint(20) NOT NULL,
  `iron` bigint(20) NOT NULL,
  `stone` bigint(20) NOT NULL,
  `lumber` bigint(20) NOT NULL,
  `goldtax` bigint(20) NOT NULL,
  `irontax` bigint(20) NOT NULL,
  `stonetax` bigint(20) NOT NULL,
  `lumbertax` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `alliancerequest`
--

CREATE TABLE `alliancerequest` (
  `id` int(11) NOT NULL,
  `allianceid` int(11) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` int(11) NOT NULL,
  `lumbermill` bigint(20) NOT NULL,
  `mine` bigint(20) NOT NULL,
  `hunterpost` bigint(20) NOT NULL,
  `barrack` bigint(20) NOT NULL,
  `stable` bigint(20) NOT NULL,
  `shootingrange` bigint(20) NOT NULL,
  `house` bigint(20) NOT NULL,
  `applefarm` bigint(20) NOT NULL,
  `dairyfarm` bigint(20) NOT NULL,
  `wheatfarm` bigint(20) NOT NULL,
  `mill` bigint(20) NOT NULL,
  `bakery` bigint(20) NOT NULL,
  `marketplace` bigint(20) NOT NULL,
  `wall` bigint(20) NOT NULL,
  `siegecamp` bigint(20) NOT NULL,
  `engineerworkshop` bigint(20) NOT NULL,
  `armory` int(11) NOT NULL,
  `weaponsmithery` int(11) NOT NULL,
  `bowyerworkshop` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `confirm`
--

CREATE TABLE `confirm` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `confirmkey` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `id` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(20) NOT NULL,
  `post` varchar(8000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `attacker` int(11) NOT NULL,
  `defender` int(11) NOT NULL,
  `gold` bigint(20) NOT NULL,
  `iron` bigint(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `marketplace`
--

CREATE TABLE `marketplace` (
  `id` int(11) NOT NULL,
  `seller` varchar(20) NOT NULL,
  `item` varchar(20) NOT NULL,
  `itemamount` bigint(20) NOT NULL,
  `price` varchar(20) NOT NULL,
  `priceamount` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `receiver` varchar(20) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `message` varchar(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `playermap`
--

CREATE TABLE `playermap` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `x` bigint(20) NOT NULL,
  `y` bigint(20) NOT NULL,
  `building` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ranking`
--

CREATE TABLE `ranking` (
  `id` int(11) NOT NULL,
  `power` bigint(20) NOT NULL,
  `overall` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
  `id` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `reply` varchar(5000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `gold` bigint(20) NOT NULL,
  `iron` bigint(20) NOT NULL,
  `stone` bigint(20) NOT NULL,
  `lumber` bigint(20) NOT NULL,
  `wheat` bigint(20) NOT NULL,
  `flour` bigint(20) NOT NULL,
  `apples` bigint(20) NOT NULL,
  `bread` bigint(20) NOT NULL,
  `cheese` bigint(20) NOT NULL,
  `meat` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `techtree`
--

CREATE TABLE `techtree` (
  `id` int(11) NOT NULL,
  `techid` int(11) NOT NULL,
  `display1` int(11) NOT NULL,
  `display2` int(11) NOT NULL,
  `path` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `treaties`
--

CREATE TABLE `treaties` (
  `id` int(11) NOT NULL,
  `user1` varchar(20) NOT NULL,
  `user2` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `worker` bigint(20) NOT NULL,
  `footman` bigint(20) NOT NULL,
  `archer` bigint(20) NOT NULL,
  `knight` bigint(20) NOT NULL,
  `horse` bigint(20) NOT NULL,
  `rifleman` bigint(20) NOT NULL,
  `pikeman` bigint(20) NOT NULL,
  `trebuchet` bigint(20) NOT NULL,
  `batteringram` bigint(20) NOT NULL,
  `siegetower` bigint(20) NOT NULL,
  `catapult` bigint(20) NOT NULL,
  `moat` bigint(20) NOT NULL,
  `murderhole` bigint(20) NOT NULL,
  `battlement` bigint(20) NOT NULL,
  `machicolation` bigint(20) NOT NULL,
  `general` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `leader` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `flag` varchar(50) NOT NULL,
  `elite` int(11) NOT NULL,
  `level` bigint(20) NOT NULL,
  `land` bigint(20) NOT NULL,
  `terrain` varchar(100) NOT NULL,
  `allianceid` int(11) NOT NULL,
  `blockade` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `inbox` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `weapon`
--

CREATE TABLE `weapon` (
  `id` int(11) NOT NULL,
  `crossbow` bigint(20) NOT NULL,
  `longbow` bigint(20) NOT NULL,
  `sword` bigint(20) NOT NULL,
  `pike` bigint(20) NOT NULL,
  `armor` bigint(20) NOT NULL,
  `shield` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `worker`
--

CREATE TABLE `worker` (
  `id` int(11) NOT NULL,
  `lumbermill` bigint(20) NOT NULL,
  `mine` bigint(20) NOT NULL,
  `hunterpost` bigint(20) NOT NULL,
  `applefarm` bigint(20) NOT NULL,
  `dairyfarm` bigint(20) NOT NULL,
  `wheatfarm` bigint(20) NOT NULL,
  `mill` bigint(20) NOT NULL,
  `bakery` bigint(20) NOT NULL,
  `armory` int(11) NOT NULL,
  `weaponsmithery` int(11) NOT NULL,
  `bowyerworkshop` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alliance`
--
ALTER TABLE `alliance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alliancerequest`
--
ALTER TABLE `alliancerequest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `confirm`
--
ALTER TABLE `confirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playermap`
--
ALTER TABLE `playermap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `techtree`
--
ALTER TABLE `techtree`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treaties`
--
ALTER TABLE `treaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weapon`
--
ALTER TABLE `weapon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alliance`
--
ALTER TABLE `alliance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `alliancerequest`
--
ALTER TABLE `alliancerequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `confirm`
--
ALTER TABLE `confirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `playermap`
--
ALTER TABLE `playermap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `techtree`
--
ALTER TABLE `techtree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `treaties`
--
ALTER TABLE `treaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `weapon`
--
ALTER TABLE `weapon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
