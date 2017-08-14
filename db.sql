-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2017 at 11:08 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `games`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `givenorder`
--

CREATE TABLE `givenorder` (
  `id` int(11) NOT NULL,
  `turnId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `orderTypeId` int(11) NOT NULL,
  `data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ordertype`
--

CREATE TABLE `ordertype` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `piece`
--

CREATE TABLE `piece` (
  `id` int(11) NOT NULL,
  `location` char(255) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `typeId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `piecetype`
--

CREATE TABLE `piecetype` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `allowedLocationTypes` varchar(255) NOT NULL,
  `attack` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `traits` int(11) NOT NULL,
  `priority` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `id` int(11) NOT NULL,
  `gameId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `tile`
--

CREATE TABLE `tile` (
  `id` int(11) NOT NULL,
  `coordinates` char(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `turn`
--

CREATE TABLE `turn` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `gameId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `givenorder`
--
ALTER TABLE `givenorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordertype`
--
ALTER TABLE `ordertype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `piece`
--
ALTER TABLE `piece`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `piecetype`
--
ALTER TABLE `piecetype`
  ADD PRIMARY KEY (`id`);

-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tile`
--
ALTER TABLE `tile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `turn`
--
ALTER TABLE `turn`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `givenorder`
--
ALTER TABLE `givenorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ordertype`
--
ALTER TABLE `ordertype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `piece`
--
ALTER TABLE `piece`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `piecetype`
--
ALTER TABLE `piecetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `tile`
--
ALTER TABLE `tile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `turn`
--
ALTER TABLE `turn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  ALTER TABLE `piece` CHANGE `location` `tileId` INT(11) NOT NULL;
  ALTER TABLE `piece` ADD `turnId` INT(11) NOT NULL AFTER `typeId`;

  ALTER TABLE `piecetype`
  DROP `allowedLocationTypes`,
  DROP `attack`,
  DROP `defense`,
  DROP `speed`,
  DROP `traits`,
  DROP `priority`;

  ALTER TABLE `piecetype` ADD `traits` TEXT NOT NULL AFTER `name`;

  CREATE TABLE `games`.`log` ( `id` INT NOT NULL , `results` TEXT NOT NULL , `service` INT NOT NULL , `origin` INT NOT NULL , `originId` INT NOT NULL , `turnId` INT NOT NULL ) ENGINE = InnoDB;

  ALTER TABLE `givenorder` CHANGE `orderTypeId` `orderType` INT(11) NOT NULL;
  ALTER TABLE `player` CHANGE `industry` `name` VARCHAR(255) NOT NULL, CHANGE `social` `color` VARCHAR(7) NOT NULL;
  ALTER TABLE `tile` ADD `gameId` INT NOT NULL AFTER `coordinates`;
  ALTER TABLE `piece` CHANGE `ownerId` `ownerId` INT(11) NULL;
  ALTER TABLE `givenOrder` CHANGE `orderType` `orderType` VARCHAR(255) NOT NULL;

CREATE TABLE `games`.`claimedObjective` ( `id` INT NOT NULL AUTO_INCREMENT , `playerId` INT NOT NULL , `turnId` INT NOT NULL , `objectiveId` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `games`.`activeObjective` ( `id` INT NOT NULL AUTO_INCREMENT , `gameId` INT NOT NULL , `turnId` INT NOT NULL , `value` INT NOT NULL , `params` TEXT NOT NULL , `type` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `game` ADD `vpLimit` INT NOT NULL AFTER `id`;
ALTER TABLE `game` ADD `active` INT NOT NULL AFTER `id`;

CREATE TABLE `games`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `roles` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`), UNIQUE (`username`)) ENGINE = InnoDB;

INSERT INTO `users` (`id`, `username`, `roles`, `password`) VALUES
(1, 'admin', 'ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==');

ALTER TABLE `player` ADD `userId` INT NOT NULL AFTER `color`;

CREATE TABLE `games`.`openGame` ( `id` INT NOT NULL AUTO_INCREMENT , `userId` INT NOT NULL , `password` VARCHAR(255) NOT NULL , `vpLimit` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `games`.`subscribedPlayer` ( `id` INT NOT NULL AUTO_INCREMENT , `userId` INT NOT NULL , `openGameId` INT NOT NULL , `name` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `subscribedPlayer` CHANGE `name` `name` VARCHAR(255) NOT NULL;
ALTER TABLE `user` CHANGE `username` `name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `player` ADD `ready` TINYINT NOT NULL AFTER `userId`;
