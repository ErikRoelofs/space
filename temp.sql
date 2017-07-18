
TRUNCATE `game`;
TRUNCATE `givenOrder`;
TRUNCATE `log`;
TRUNCATE `piece`;
TRUNCATE `pieceType`;
TRUNCATE `player`;
TRUNCATE `tile`;
TRUNCATE `turn`;


-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2017 at 04:00 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `games`
--

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`) VALUES
(1);

--
-- Dumping data for table `givenOrder`
--

INSERT INTO `givenOrder` (`id`, `turnId`, `ownerId`, `orderType`, `data`) VALUES
(1, 1, 1, 'tactical', '{"tile":35,"pieces":[25,26],"newPieces":[]}'),
(2, 1, 5, 'tactical', '{"tile":35,"pieces":[54,55],"newPieces":[]}');

--
-- Dumping data for table `piece`
--

INSERT INTO `piece` (`id`, `ownerId`, `typeId`, `turnId`, `tileId`) VALUES
(1, 2, 1, 1, 1),
(2, 3, 1, 1, 4),
(3, NULL, 1, 1, 7),
(4, NULL, 1, 1, 8),
(5, NULL, 1, 1, 9),
(6, NULL, 1, 1, 10),
(7, NULL, 1, 1, 12),
(8, NULL, 1, 1, 13),
(9, NULL, 1, 1, 14),
(10, 4, 1, 1, 16),
(11, NULL, 1, 1, 17),
(12, NULL, 1, 1, 19),
(13, NULL, 1, 1, 20),
(14, 6, 1, 1, 22),
(15, NULL, 1, 1, 23),
(16, NULL, 1, 1, 24),
(17, NULL, 1, 1, 25),
(18, NULL, 1, 1, 26),
(19, NULL, 1, 1, 27),
(20, NULL, 1, 1, 29),
(21, NULL, 1, 1, 30),
(22, NULL, 1, 1, 32),
(23, 1, 1, 1, 34),
(24, 5, 1, 1, 37),
(25, 1, 2, 1, 34),
(26, 1, 2, 1, 34),
(27, 1, 3, 1, 34),
(28, 1, 4, 1, 34),
(29, 1, 4, 1, 34),
(30, 1, 4, 1, 34),
(31, 1, 4, 1, 34),
(32, 2, 2, 1, 1),
(33, 2, 2, 1, 1),
(34, 2, 3, 1, 1),
(35, 2, 4, 1, 1),
(36, 2, 4, 1, 1),
(37, 2, 4, 1, 1),
(38, 2, 4, 1, 1),
(39, 3, 2, 1, 4),
(40, 3, 2, 1, 4),
(41, 3, 3, 1, 4),
(42, 3, 4, 1, 4),
(43, 3, 4, 1, 4),
(44, 3, 4, 1, 4),
(45, 3, 4, 1, 4),
(46, 4, 2, 1, 16),
(47, 4, 2, 1, 16),
(48, 4, 3, 1, 16),
(49, 4, 4, 1, 16),
(50, 4, 4, 1, 16),
(51, 4, 4, 1, 16),
(52, 4, 4, 1, 16),
(53, 5, 2, 1, 37),
(54, 5, 2, 1, 37),
(55, 5, 3, 1, 37),
(56, 5, 4, 1, 37),
(57, 5, 4, 1, 37),
(58, 5, 4, 1, 37),
(59, 5, 4, 1, 37),
(60, 6, 2, 1, 22),
(61, 6, 2, 1, 22),
(62, 6, 3, 1, 22),
(63, 6, 4, 1, 22),
(64, 6, 4, 1, 22),
(65, 6, 4, 1, 22),
(66, 6, 4, 1, 22);

--
-- Dumping data for table `pieceType`
--

INSERT INTO `pieceType` (`id`, `name`, `traits`) VALUES
(1, 'Planet', 'a:6:{i:0;O:23:"Plu\\PieceTrait\\Grounded":0:{}i:1;O:25:"Plu\\PieceTrait\\Transports":1:{s:32:"\0Plu\\PieceTrait\\Transports\0value";i:100;}i:2;O:25:"Plu\\PieceTrait\\Capturable":0:{}i:3;O:29:"Plu\\PieceTrait\\GivesResources":2:{s:11:"\0*\0industry";i:2;s:9:"\0*\0social";i:2;}i:4;O:24:"Plu\\PieceTrait\\TileLimit":1:{s:8:"\0*\0limit";i:1;}i:5;O:27:"Plu\\PieceTrait\\BuildsPieces":1:{s:12:"\0*\0typeNames";a:1:{i:0;s:9:"SpaceDock";}}}'),
(2, 'Destroyer', 'a:6:{i:0;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:1;O:21:"Plu\\PieceTrait\\Mobile":1:{s:28:"\0Plu\\PieceTrait\\Mobile\0value";i:2;}i:2;O:33:"Plu\\PieceTrait\\FightsSpaceBattles":2:{s:43:"\0Plu\\PieceTrait\\FightsSpaceBattles\0priority";i:1;s:42:"\0Plu\\PieceTrait\\FightsSpaceBattles\0defense";i:1;}i:3;O:26:"Plu\\PieceTrait\\FlakCannons":2:{s:33:"\0Plu\\PieceTrait\\FlakCannons\0shots";i:2;s:37:"\0Plu\\PieceTrait\\FlakCannons\0firepower";i:2;}i:4;O:25:"Plu\\PieceTrait\\MainCannon":2:{s:32:"\0Plu\\PieceTrait\\MainCannon\0shots";i:1;s:36:"\0Plu\\PieceTrait\\MainCannon\0firepower";i:2;}i:5;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";i:1;}}'),
(3, 'Carrier', 'a:6:{i:0;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:1;O:21:"Plu\\PieceTrait\\Mobile":1:{s:28:"\0Plu\\PieceTrait\\Mobile\0value";i:1;}i:2;O:25:"Plu\\PieceTrait\\Transports":1:{s:32:"\0Plu\\PieceTrait\\Transports\0value";i:6;}i:3;O:33:"Plu\\PieceTrait\\FightsSpaceBattles":2:{s:43:"\0Plu\\PieceTrait\\FightsSpaceBattles\0priority";i:3;s:42:"\0Plu\\PieceTrait\\FightsSpaceBattles\0defense";i:1;}i:4;O:25:"Plu\\PieceTrait\\MainCannon":2:{s:32:"\0Plu\\PieceTrait\\MainCannon\0shots";i:1;s:36:"\0Plu\\PieceTrait\\MainCannon\0firepower";i:2;}i:5;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";i:3;}}'),
(4, 'Fighter', 'a:6:{i:0;O:20:"Plu\\PieceTrait\\Cargo":0:{}i:1;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:2;O:33:"Plu\\PieceTrait\\FightsSpaceBattles":2:{s:43:"\0Plu\\PieceTrait\\FightsSpaceBattles\0priority";i:1;s:42:"\0Plu\\PieceTrait\\FightsSpaceBattles\0defense";i:1;}i:3;O:25:"Plu\\PieceTrait\\MainCannon":2:{s:32:"\0Plu\\PieceTrait\\MainCannon\0shots";i:1;s:36:"\0Plu\\PieceTrait\\MainCannon\0firepower";i:2;}i:4;O:19:"Plu\\PieceTrait\\Tiny":0:{}i:5;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";d:0.5;}}'),
(5, 'SpaceDock', 'a:4:{i:0;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:1;O:27:"Plu\\PieceTrait\\BuildsPieces":1:{s:12:"\0*\0typeNames";a:7:{i:0;s:9:"Destroyer";i:1;s:7:"Fighter";i:2;s:7:"Carrier";i:3;s:7:"Cruiser";i:4;s:11:"Dreadnought";i:5;s:11:"GroundForce";i:6;s:13:"DefenseSystem";}}i:2;O:24:"Plu\\PieceTrait\\TileLimit":1:{s:8:"\0*\0limit";i:1;}i:3;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";i:3;}}'),
(6, 'Dreadnought', 'a:5:{i:0;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:1;O:21:"Plu\\PieceTrait\\Mobile":1:{s:28:"\0Plu\\PieceTrait\\Mobile\0value";i:1;}i:2;O:33:"Plu\\PieceTrait\\FightsSpaceBattles":2:{s:43:"\0Plu\\PieceTrait\\FightsSpaceBattles\0priority";i:2;s:42:"\0Plu\\PieceTrait\\FightsSpaceBattles\0defense";i:2;}i:3;O:25:"Plu\\PieceTrait\\MainCannon":2:{s:32:"\0Plu\\PieceTrait\\MainCannon\0shots";i:1;s:36:"\0Plu\\PieceTrait\\MainCannon\0firepower";i:6;}i:4;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";i:5;}}'),
(7, 'Cruiser', 'a:5:{i:0;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:1;O:21:"Plu\\PieceTrait\\Mobile":1:{s:28:"\0Plu\\PieceTrait\\Mobile\0value";i:2;}i:2;O:33:"Plu\\PieceTrait\\FightsSpaceBattles":2:{s:43:"\0Plu\\PieceTrait\\FightsSpaceBattles\0priority";i:2;s:42:"\0Plu\\PieceTrait\\FightsSpaceBattles\0defense";i:1;}i:3;O:25:"Plu\\PieceTrait\\MainCannon":2:{s:32:"\0Plu\\PieceTrait\\MainCannon\0shots";i:1;s:36:"\0Plu\\PieceTrait\\MainCannon\0firepower";i:4;}i:4;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";i:2;}}'),
(8, 'GroundForce', 'a:5:{i:0;O:20:"Plu\\PieceTrait\\Cargo":0:{}i:1;O:34:"Plu\\PieceTrait\\FightsGroundBattles":2:{s:44:"\0Plu\\PieceTrait\\FightsGroundBattles\0priority";i:1;s:43:"\0Plu\\PieceTrait\\FightsGroundBattles\0defense";i:1;}i:2;O:27:"Plu\\PieceTrait\\GroundCannon":2:{s:34:"\0Plu\\PieceTrait\\GroundCannon\0shots";i:1;s:38:"\0Plu\\PieceTrait\\GroundCannon\0firepower";i:2;}i:3;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";d:0.5;}i:4;O:23:"Plu\\PieceTrait\\Grounded":0:{}}'),
(9, 'DefenseSystem', 'a:7:{i:0;O:20:"Plu\\PieceTrait\\Cargo":0:{}i:1;O:24:"Plu\\PieceTrait\\Torpedoes":2:{s:31:"\0Plu\\PieceTrait\\Torpedoes\0shots";i:1;s:35:"\0Plu\\PieceTrait\\Torpedoes\0firepower";i:5;}i:2;O:24:"Plu\\PieceTrait\\Artillery":2:{s:31:"\0Plu\\PieceTrait\\Artillery\0shots";i:1;s:35:"\0Plu\\PieceTrait\\Artillery\0firepower";i:5;}i:3;O:24:"Plu\\PieceTrait\\TileLimit":1:{s:8:"\0*\0limit";i:3;}i:4;O:47:"Plu\\PieceTrait\\BuildRequirements\\CostsResources":1:{s:55:"\0Plu\\PieceTrait\\BuildRequirements\\CostsResources\0amount";i:2;}i:5;O:25:"Plu\\PieceTrait\\Spaceborne":0:{}i:6;O:23:"Plu\\PieceTrait\\Grounded":0:{}}');

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`id`, `gameId`, `name`, `color`) VALUES
(1, 1, 'John', '#ff0000'),
(2, 1, 'Paul', '#ffff00'),
(3, 1, 'Anna', '#ff00ff'),
(4, 1, 'Sarah', '#00ff00'),
(5, 1, 'Mike', '#00ffff'),
(6, 1, 'Amber', '#0000ff');

--
-- Dumping data for table `tile`
--

INSERT INTO `tile` (`id`, `gameId`, `coordinates`) VALUES
(1, 1, '[0,0]'),
(2, 1, '[0,1]'),
(3, 1, '[0,2]'),
(4, 1, '[0,3]'),
(5, 1, '[1,0]'),
(6, 1, '[1,1]'),
(7, 1, '[1,2]'),
(8, 1, '[1,3]'),
(9, 1, '[1,4]'),
(10, 1, '[2,0]'),
(11, 1, '[2,1]'),
(12, 1, '[2,2]'),
(13, 1, '[2,3]'),
(14, 1, '[2,4]'),
(15, 1, '[2,5]'),
(16, 1, '[3,0]'),
(17, 1, '[3,1]'),
(18, 1, '[3,2]'),
(19, 1, '[3,3]'),
(20, 1, '[3,4]'),
(21, 1, '[3,5]'),
(22, 1, '[3,6]'),
(23, 1, '[4,1]'),
(24, 1, '[4,2]'),
(25, 1, '[4,3]'),
(26, 1, '[4,4]'),
(27, 1, '[4,5]'),
(28, 1, '[4,6]'),
(29, 1, '[5,2]'),
(30, 1, '[5,3]'),
(31, 1, '[5,4]'),
(32, 1, '[5,5]'),
(33, 1, '[5,6]'),
(34, 1, '[6,3]'),
(35, 1, '[6,4]'),
(36, 1, '[6,5]'),
(37, 1, '[6,6]');

--
-- Dumping data for table `turn`
--

INSERT INTO `turn` (`id`, `number`, `gameId`) VALUES
(1, 1, 1);


CREATE TABLE `games`.`resourceclaim` ( `id` INT NOT NULL , `ownerId` INT NOT NULL , `resource` VARCHAR(255) NOT NULL , `turnId` INT NOT NULL , `amount` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `piece` ADD `traits` TEXT NOT NULL AFTER `turnId`;