-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for austingastropub
CREATE DATABASE IF NOT EXISTS `austingastropub` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `austingastropub`;

-- Dumping structure for table austingastropub.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `Employee_ID` varchar(9) NOT NULL,
  `Employee_Role` text NOT NULL,
  `Employee_Name` text NOT NULL,
  `Employee_Email` varchar(255) NOT NULL,
  `Employee_PassKey` varchar(6) NOT NULL,
  `Employee_PhoneNumber` bigint(11) NOT NULL,
  `Employee_Status` varchar(20) NOT NULL,
  PRIMARY KEY (`Employee_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.employees: ~4 rows (approximately)
INSERT INTO `employees` (`Employee_ID`, `Employee_Role`, `Employee_Name`, `Employee_Email`, `Employee_PassKey`, `Employee_PhoneNumber`, `Employee_Status`) VALUES
	('123456789', 'POS Staff Management', 'Dominic Xandy Adino', 'dominicadino23@gmail.com', '123456', 9257717724, 'Active'),
	('395635613', 'Inventory Staff Management', 'Patrick Star', 'patrickstar@gmail.com', '654321', 9257717724, 'Active'),
	('598353747', 'Administrator', 'John Doe', 'admin@gmail.com', '123456', 9257717724, 'Active'),
	('987654321', 'Employee ', 'Spongebob ', 'dominicxandy.adino.cics@ust.edu.ph', '123456', 9257717724, 'Inactive');

-- Dumping structure for table austingastropub.tbl_addons
CREATE TABLE IF NOT EXISTS `tbl_addons` (
  `addonID` int(11) NOT NULL AUTO_INCREMENT,
  `addonName` varchar(25) NOT NULL,
  `addonPrice` decimal(10,2) NOT NULL,
  `menuID` int(11) NOT NULL,
  PRIMARY KEY (`addonID`),
  KEY `FK_tbl_addons_tbl_menuclass` (`menuID`),
  CONSTRAINT `FK_tbl_addons_tbl_menuclass` FOREIGN KEY (`menuID`) REFERENCES `tbl_menuclass` (`menuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_addons: ~5 rows (approximately)
INSERT INTO `tbl_addons` (`addonID`, `addonName`, `addonPrice`, `menuID`) VALUES
	(1, 'Espresso Shot', 40.00, 1),
	(2, 'Whip Cream', 30.00, 1),
	(3, 'Oatmilk', 40.00, 1),
	(4, 'Sauces', 20.00, 1),
	(5, 'Syrup', 15.00, 1);

-- Dumping structure for table austingastropub.tbl_categories
CREATE TABLE IF NOT EXISTS `tbl_categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(40) NOT NULL,
  `categoryIcon` varchar(50) NOT NULL,
  `menuID` int(11) DEFAULT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_categories: ~32 rows (approximately)
INSERT INTO `tbl_categories` (`categoryID`, `categoryName`, `categoryIcon`, `menuID`) VALUES
	(1, 'Hot', 'fi fi-rr-mug-hot-alt', 1),
	(2, 'Iced', 'fi fi-rr-coffee', 1),
	(3, 'Tea and Refresher', 'fi fi-rr-mug-tea', 1),
	(4, 'Slushies', 'fi fi-rr-blender', 1),
	(5, 'Signature Drinks', 'fi fi-rr-sparkles', 1),
	(6, 'Non-Coffee', 'fi fi-rr-milk', 1),
	(7, 'Iced Blended Coffee', 'fi fi-rr-snowflake', 1),
	(8, 'Signature Cocktails', 'fi fi-rr-sparkles', 2),
	(9, 'Classic Cocktails', 'fi fi-rr-martini-glass-citrus', 2),
	(10, 'Shooters', 'fi fi-rr-glass-whiskey-rocks', 2),
	(11, 'Beers', 'fi fi-rr-beer', 2),
	(12, 'Premium Bottles', 'fi fi-rr-wine-bottle', 2),
	(13, 'Drinks', 'fi fi-rr-jug-alt', 2),
	(14, 'Starter', 'fi fi-rr-sandwich', 2),
	(15, 'Chicken', 'fi fi-rr-drumstick', 2),
	(16, 'Pork', 'fi fi-rr-bacon', 2),
	(17, 'Seafoods', 'fi fi-rr-shrimp', 2),
	(18, 'Rice and Noodles', 'fi fi-rr-bowl-rice', 2),
	(19, 'Beef', 'fi fi-rr-steak', 2),
	(20, 'Vegetables', 'fi fi-rr-leafy-green', 2),
	(21, 'Dessert', 'fi fi-rr-cupcake-alt', 2),
	(26, 'Beef', 'fi fi-rr-steak', 3),
	(27, 'Pork', 'fi fi-rr-bacon', 3),
	(28, 'Chicken', 'fi fi-rr-drumstick', 3),
	(29, 'Seafoods', 'fi fi-rr-shrimp', 3),
	(30, 'Vegetables', 'fi fi-rr-leafy-green', 3),
	(31, 'Pasta and Noodles', 'fi fi-rr-fusilli', 3),
	(33, 'Starters', 'fi fi-rr-french-fries', 1),
	(34, 'Pancakes', 'fi fi-rr-pancakes', 1),
	(35, 'Rice Meal', 'fi fi-rr-bowl-rice', 1),
	(36, 'Pasta', 'fi fi-rr-fusilli', 1),
	(37, 'Salad', 'fi fi-rr-salad', 1);

-- Dumping structure for table austingastropub.tbl_menu
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `productName` varchar(50) NOT NULL,
  `productImage` varchar(75) DEFAULT NULL,
  `menuID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  `productPrice` decimal(10,2) NOT NULL,
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_menu: ~199 rows (approximately)
INSERT INTO `tbl_menu` (`productID`, `productName`, `productImage`, `menuID`, `categoryID`, `productPrice`) VALUES
	(1, 'Creamy Beef with Mushroom', '', 3, 26, 2300.00),
	(2, 'Lengua with Mushroom', '', 3, 26, 2800.00),
	(3, 'Spicy Beef Caldereta', '', 3, 26, 2150.00),
	(4, 'Roast Beef with Mashed Potato', '', 3, 26, 2450.00),
	(5, 'Beef Salpicao', '', 3, 26, 2250.00),
	(6, 'Sweet Baby Back Ribs', NULL, 3, 27, 2150.00),
	(7, 'Bagnet Kare-kare', NULL, 3, 27, 2200.00),
	(8, 'Pork Belly Sisig', NULL, 3, 27, 1950.00),
	(9, 'Lumpiang Shanghai', NULL, 3, 27, 1800.00),
	(10, 'Baked Buttered Chicken with Mashed Potato', NULL, 3, 28, 1900.00),
	(11, 'Orange Chicken', NULL, 3, 28, 1800.00),
	(12, 'Cordon Bleu', NULL, 3, 28, 1900.00),
	(13, 'Chicken Teriyaki with Bean Sprout', NULL, 3, 28, 1750.00),
	(14, 'Grilled Tuna with Lemon Butter Sauce', NULL, 3, 29, 2800.00),
	(15, 'Fish Florentine with Spinach & Onion', NULL, 3, 29, 1800.00),
	(16, 'Prawn Thermidor', NULL, 3, 29, 2750.00),
	(17, 'Garlic Shrimp', NULL, 3, 29, 2750.00),
	(18, 'Mixed Vegetables with Seafood', NULL, 3, 30, 1800.00),
	(19, 'Creamy Sipo Egg', NULL, 3, 30, 1800.00),
	(20, 'Brocolli with Garlic', NULL, 3, 30, 1700.00),
	(21, 'Baked Lasagna', NULL, 3, 31, 2500.00),
	(22, 'Chicken Alfredo', NULL, 3, 31, 2500.00),
	(23, 'Garlic Shrimp Pasta', NULL, 3, 31, 2750.00),
	(24, 'Special Sotanghon Guisado', NULL, 3, 31, 1950.00),
	(25, 'Seafood Pancit Canton', NULL, 3, 31, 1800.00),
	(26, 'Americano', NULL, 1, 1, 120.00),
	(27, 'Cafe Latte', NULL, 1, 1, 140.00),
	(28, 'Caramel Macchiato', NULL, 1, 1, 150.00),
	(29, 'Salted Caramel Macchiato', NULL, 1, 1, 150.00),
	(30, 'Mocha', NULL, 1, 1, 150.00),
	(31, 'Spanish Latte', NULL, 1, 1, 150.00),
	(32, 'White Mocha', NULL, 1, 1, 150.00),
	(33, 'Kettle Corn Latte', NULL, 1, 1, 150.00),
	(34, 'Americano', NULL, 1, 2, 120.00),
	(35, 'Cloud Americano', NULL, 1, 2, 140.00),
	(36, 'Cafe Latte', NULL, 1, 2, 140.00),
	(37, 'Biscoff Latte', NULL, 1, 2, 190.00),
	(38, 'Butterscotch Latte', NULL, 1, 2, 160.00),
	(39, 'Caramel Macchiato', NULL, 1, 2, 160.00),
	(40, 'Dirty Matcha Latte', NULL, 1, 2, 160.00),
	(41, 'Kettle Corn Latte', NULL, 1, 2, 160.00),
	(42, 'Mocha', NULL, 1, 2, 160.00),
	(43, 'Seasalt Latte', NULL, 1, 2, 160.00),
	(44, 'Salted Caramel Macchiato', NULL, 1, 2, 160.00),
	(45, 'Spanish Latte', NULL, 1, 2, 160.00),
	(46, 'White Mocha', NULL, 1, 2, 160.00),
	(47, 'Hibiscus Tea', NULL, 1, 3, 160.00),
	(48, 'Hibiscus Strawberry & Lemon', NULL, 1, 3, 160.00),
	(49, 'Butterfly Pea Strawberry', NULL, 1, 3, 160.00),
	(50, 'Iced Shaken Raspberry Lemon Tea', NULL, 1, 3, 180.00),
	(51, 'Iced Shaken Pea Lemon Tea', NULL, 1, 3, 180.00),
	(52, 'Basil Mint', NULL, 1, 3, 160.00),
	(53, 'Mixed Berries Slush', NULL, 1, 4, 185.00),
	(54, 'Kiwi Slush', NULL, 1, 4, 185.00),
	(55, 'Crema Bruciata Latte', NULL, 1, 5, 180.00),
	(56, 'Tre Latte', NULL, 1, 5, 180.00),
	(57, 'Coconut Kopi Latte', NULL, 1, 5, 180.00),
	(58, 'Honey Oat Latte', NULL, 1, 5, 180.00),
	(59, 'Matcha Latte', NULL, 1, 6, 150.00),
	(60, 'Strawberry Latte', NULL, 1, 6, 150.00),
	(61, 'Strawberry Matcha Latte', NULL, 1, 6, 150.00),
	(62, 'Signature Chocolate', NULL, 1, 6, 140.00),
	(63, 'Butterscotch', NULL, 1, 7, 185.00),
	(64, 'Biscoff', NULL, 1, 7, 200.00),
	(65, 'Caramel', NULL, 1, 7, 185.00),
	(66, 'Dark Mocha', NULL, 1, 7, 185.00),
	(67, 'Java Chip', NULL, 1, 7, 185.00),
	(68, 'Salted Caramel', NULL, 1, 7, 185.00),
	(69, 'Chocolate Chip Cream', NULL, 1, 6, 185.00),
	(70, 'Strawberry Cream', NULL, 1, 6, 185.00),
	(71, 'Matcha Cream', NULL, 1, 6, 185.00),
	(72, 'Quesadillas', NULL, 1, 33, 170.00),
	(73, 'Chicken Fingers with Fries', NULL, 1, 33, 250.00),
	(74, 'Fish and Chips', NULL, 1, 33, 220.00),
	(75, 'Ceasar Salad', NULL, 1, 37, 230.00),
	(76, 'Pancake and Glazed Chicken', NULL, 1, 34, 270.00),
	(77, 'Pancake Bacon and Egg', NULL, 1, 34, 260.00),
	(78, 'Clubhouse Sandwich', NULL, 1, 34, 270.00),
	(79, 'Four Cheese Pizza', NULL, 1, 34, 350.00),
	(80, 'Bacon and Mushroom Pizza', NULL, 1, 34, 330.00),
	(81, 'Sausage and Bacon Pizza', NULL, 1, 34, 350.00),
	(82, 'Orange Chicken', NULL, 1, 35, 220.00),
	(83, 'Fried Chicken', NULL, 1, 35, 220.00),
	(84, 'Beef Tapa', NULL, 1, 35, 250.00),
	(85, 'Sausage and Egg', NULL, 1, 35, 220.00),
	(86, 'Spam and Egg', NULL, 1, 35, 200.00),
	(87, 'Baked Lasagna', NULL, 1, 36, 230.00),
	(88, 'Chicken Alfredo', NULL, 1, 36, 230.00),
	(89, 'Garlic Shrimp Pasta', NULL, 1, 36, 250.00),
	(90, 'Fusilli Pasta', NULL, 1, 36, 230.00),
	(91, 'Austin\'s Tropical Punch', NULL, 2, 8, 280.00),
	(92, 'Austin\'s Blue Bayou', NULL, 2, 8, 260.00),
	(93, 'Austin\'s Strawberry Punch', NULL, 2, 8, 280.00),
	(94, 'Austin\'s Apple Bees', NULL, 2, 8, 260.00),
	(95, 'Amaretto Sour', NULL, 2, 9, 220.00),
	(96, 'Classic Margarita', NULL, 2, 9, 200.00),
	(97, 'Frozen Margarita', NULL, 2, 9, 280.00),
	(98, 'Mojito Classic', NULL, 2, 9, 200.00),
	(99, 'Strawberry Mojito', NULL, 2, 9, 220.00),
	(100, 'Tequila Sunrise', NULL, 2, 9, 180.00),
	(101, 'Kamikaze', NULL, 2, 9, 170.00),
	(102, 'Blue Hawaiian', NULL, 2, 9, 200.00),
	(103, 'Pina Colada', NULL, 2, 9, 180.00),
	(104, 'Sex on the Beach', NULL, 2, 9, 200.00),
	(105, 'Cosmopolitan', NULL, 2, 9, 180.00),
	(106, 'Long Island Tea', NULL, 2, 9, 180.00),
	(107, 'Sangria', NULL, 2, 9, 250.00),
	(108, 'BS2', NULL, 2, 10, 150.00),
	(109, 'Blow Job', NULL, 2, 10, 150.00),
	(110, 'Flaming Lamborghini', NULL, 2, 10, 230.00),
	(111, 'Blue Kamikaze', NULL, 2, 10, 100.00),
	(112, 'American Flag', NULL, 2, 10, 100.00),
	(113, 'Tequila Shot', NULL, 2, 10, 125.00),
	(114, 'San Mig Apple', NULL, 2, 11, 70.00),
	(115, 'Smirnoff Mule', NULL, 2, 11, 90.00),
	(116, 'San Mig Light', NULL, 2, 11, 80.00),
	(117, 'Pale Pilsen', NULL, 2, 11, 80.00),
	(118, 'Red Horse', NULL, 2, 11, 80.00),
	(119, 'Super Dry', NULL, 2, 11, 110.00),
	(120, 'Heineken', NULL, 2, 11, 120.00),
	(121, 'Corona Extra', NULL, 2, 11, 150.00),
	(122, 'Jack Daniels 700ml', NULL, 2, 12, 1850.00),
	(123, 'Jack Daniels Honey 700ml', NULL, 2, 12, 1800.00),
	(124, 'Jim Beam 700ml', NULL, 2, 12, 1500.00),
	(125, 'Evan Williams 750ml', NULL, 2, 12, 1400.00),
	(126, 'Bacardi Gold 750ml', NULL, 2, 12, 1200.00),
	(127, 'Bacardi Superior 750ml', NULL, 2, 12, 1200.00),
	(128, 'Captain Morgan Gold 750ml', NULL, 2, 12, 1150.00),
	(129, 'Jose Cuervo Gold 700ml', NULL, 2, 12, 1600.00),
	(130, 'Absolut Vodka 700ml', NULL, 2, 12, 1200.00),
	(131, 'Jagermeister 1L', NULL, 2, 12, 1800.00),
	(132, 'Tequila Rose 750ml', NULL, 2, 12, 1800.00),
	(133, 'Sprite(Pitcher)', NULL, 2, 13, 250.00),
	(134, 'Coke(Pitcher)', NULL, 2, 13, 250.00),
	(135, 'Bottled Water', NULL, 2, 13, 45.00),
	(136, 'Nachos', NULL, 2, 14, 250.00),
	(137, 'Mini Sausage', NULL, 2, 14, 300.00),
	(138, 'Baked Scallops', NULL, 2, 14, 280.00),
	(139, 'Chicharon Bulaklak', NULL, 2, 14, 320.00),
	(140, 'Baked Tahong', NULL, 2, 14, 280.00),
	(141, 'Salted Calamares', NULL, 2, 14, 290.00),
	(142, 'Kilawin Tuna', NULL, 2, 14, 400.00),
	(143, 'Salmon Sashimi', NULL, 2, 14, 420.00),
	(144, 'Tuna Sashimi', NULL, 2, 14, 400.00),
	(145, 'Squid Salt and Pepper', NULL, 2, 14, 290.00),
	(146, 'Pork Barbecue Skewers', NULL, 2, 14, 300.00),
	(147, 'Garlic Parmesan Chicken Skewers', NULL, 2, 14, 300.00),
	(148, 'Buttered Corn', NULL, 2, 14, 200.00),
	(149, 'Dynamite', NULL, 2, 14, 250.00),
	(150, 'Sizzling Tofu', NULL, 2, 14, 250.00),
	(151, 'Chicken Honey Glazed', NULL, 2, 15, 340.00),
	(152, 'Chicken Cordon Bleu', NULL, 2, 15, 370.00),
	(153, 'Chicken Kebab', NULL, 2, 15, 350.00),
	(154, 'Baked Buttered Chicken with Mashed Potato', NULL, 2, 15, 370.00),
	(155, 'Chicken Inasal', NULL, 2, 15, 400.00),
	(156, 'Stuffed Chicken with Cheese Sauce', NULL, 2, 15, 370.00),
	(157, 'Teriyaki Chicken with Bean Sprout', NULL, 2, 15, 340.00),
	(158, 'Orange Chicken', NULL, 2, 15, 350.00),
	(159, 'Buttered Chicken', NULL, 2, 15, 350.00),
	(160, 'Baby Back Ribs', NULL, 2, 16, 470.00),
	(161, 'Crispy Pata', NULL, 2, 16, 790.00),
	(162, 'Pork Sinigang', NULL, 2, 16, 380.00),
	(163, 'Bagnet Kare-kare', NULL, 2, 16, 430.00),
	(164, 'Pork Belly Sisig', NULL, 2, 16, 380.00),
	(165, 'Crispy Liempo', NULL, 2, 16, 400.00),
	(166, 'Sweet and Sour Pork', NULL, 2, 16, 390.00),
	(167, 'Lumpia Shanghai', NULL, 2, 16, 350.00),
	(168, 'Grilled Tuna with Lemon Buttered Sauce', NULL, 2, 17, 450.00),
	(169, 'Fish Florentine with Onion and Spinach', NULL, 2, 17, 350.00),
	(170, 'Cheesy Baked Bangus', NULL, 2, 17, 350.00),
	(171, 'Grilled Stuffed Squid', NULL, 2, 17, 300.00),
	(172, 'Garlic Shrimp', NULL, 2, 17, 430.00),
	(173, 'Bangus Ala Pobre', NULL, 2, 17, 320.00),
	(174, 'Prawn Thermidor', NULL, 2, 17, 450.00),
	(175, 'Salmon Head Sinigang sa Miso', NULL, 2, 17, 450.00),
	(176, 'Austin\'s Fried Rice', NULL, 2, 18, 300.00),
	(177, 'Garlic Fried Rice', NULL, 2, 18, 220.00),
	(178, 'Plain Rice', NULL, 2, 18, 40.00),
	(179, 'Salted Egg Fried Rice', NULL, 2, 18, 250.00),
	(180, 'Austin\'s Special Sotanghon Guisado', NULL, 2, 18, 380.00),
	(181, 'Crispy Palabok', NULL, 2, 18, 380.00),
	(182, 'Seafood Pancit Canton', NULL, 2, 18, 350.00),
	(183, 'Spicy Beef Caldereta', NULL, 2, 19, 420.00),
	(184, 'Roast Beef with Mashed Potato', NULL, 2, 19, 480.00),
	(185, 'Lengua with Mushroom', NULL, 2, 19, 470.00),
	(186, 'Creamy Beef with Mushroom', NULL, 2, 19, 450.00),
	(187, 'Beef Salpicao', NULL, 2, 19, 440.00),
	(188, 'Beef Brocolli', NULL, 2, 19, 440.00),
	(189, 'Korean Beef with Leeks', NULL, 2, 19, 410.00),
	(190, 'Beef Asado', NULL, 2, 19, 420.00),
	(191, 'Beef Balbacua', NULL, 2, 19, 400.00),
	(192, 'Brocolli with Garlic', NULL, 2, 20, 330.00),
	(193, 'Mixed Vegtables with Seafoods', NULL, 2, 20, 350.00),
	(194, 'Creamy Sipo Egg', NULL, 2, 20, 350.00),
	(195, 'Stuffed Eggplant', NULL, 2, 20, 290.00),
	(196, 'Gising Gising', NULL, 2, 20, 290.00),
	(197, 'Leche Flan with Macapuno', NULL, 2, 20, 250.00),
	(198, 'Sago Mango Panacotta', NULL, 2, 20, 250.00),
	(199, 'Peach Mango Crepe', NULL, 2, 20, 280.00);

-- Dumping structure for table austingastropub.tbl_menuclass
CREATE TABLE IF NOT EXISTS `tbl_menuclass` (
  `menuID` int(11) NOT NULL AUTO_INCREMENT,
  `menuName` varchar(50) NOT NULL,
  PRIMARY KEY (`menuID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_menuclass: ~3 rows (approximately)
INSERT INTO `tbl_menuclass` (`menuID`, `menuName`) VALUES
	(1, 'Coffee Menu'),
	(2, 'Gastro Pub Menu'),
	(3, 'Party Tray Menu');

-- Dumping structure for table austingastropub.tbl_menutoaddons
CREATE TABLE IF NOT EXISTS `tbl_menutoaddons` (
  `menuAddonID` int(11) NOT NULL AUTO_INCREMENT,
  `productID` int(11) DEFAULT NULL,
  `addonID` int(11) DEFAULT NULL,
  PRIMARY KEY (`menuAddonID`),
  KEY `FK_tbl_menutoaddons_tbl_menu` (`productID`),
  KEY `FK_tbl_menutoaddons_tbl_addons` (`addonID`),
  CONSTRAINT `FK_tbl_menutoaddons_tbl_addons` FOREIGN KEY (`addonID`) REFERENCES `tbl_addons` (`addonID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_tbl_menutoaddons_tbl_menu` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_menutoaddons: ~230 rows (approximately)
INSERT INTO `tbl_menutoaddons` (`menuAddonID`, `productID`, `addonID`) VALUES
	(1, 34, 1),
	(2, 34, 2),
	(3, 34, 3),
	(4, 34, 4),
	(5, 34, 5),
	(6, 35, 1),
	(7, 35, 2),
	(8, 35, 3),
	(9, 35, 4),
	(10, 35, 5),
	(11, 36, 1),
	(12, 36, 2),
	(13, 36, 3),
	(14, 36, 4),
	(15, 36, 5),
	(16, 37, 1),
	(17, 37, 2),
	(18, 37, 3),
	(19, 37, 4),
	(20, 37, 5),
	(21, 38, 1),
	(22, 38, 2),
	(23, 38, 3),
	(24, 38, 4),
	(25, 38, 5),
	(26, 39, 1),
	(27, 39, 2),
	(28, 39, 3),
	(29, 39, 4),
	(30, 39, 5),
	(31, 40, 1),
	(32, 40, 2),
	(33, 40, 3),
	(34, 40, 4),
	(35, 40, 5),
	(36, 41, 1),
	(37, 41, 2),
	(38, 41, 3),
	(39, 41, 4),
	(40, 41, 5),
	(41, 42, 1),
	(42, 42, 2),
	(43, 42, 3),
	(44, 42, 4),
	(45, 42, 5),
	(46, 43, 1),
	(47, 43, 2),
	(48, 43, 3),
	(49, 43, 4),
	(50, 43, 5),
	(51, 44, 1),
	(52, 44, 2),
	(53, 44, 3),
	(54, 44, 4),
	(55, 44, 5),
	(56, 45, 1),
	(57, 45, 2),
	(58, 45, 3),
	(59, 45, 4),
	(60, 45, 5),
	(61, 46, 1),
	(62, 46, 2),
	(63, 46, 3),
	(64, 46, 4),
	(65, 46, 5),
	(66, 47, 1),
	(67, 47, 2),
	(68, 47, 3),
	(69, 47, 4),
	(70, 47, 5),
	(71, 48, 1),
	(72, 48, 2),
	(73, 48, 3),
	(74, 48, 4),
	(75, 48, 5),
	(76, 49, 1),
	(77, 49, 2),
	(78, 49, 3),
	(79, 49, 4),
	(80, 49, 5),
	(81, 50, 1),
	(82, 50, 2),
	(83, 50, 3),
	(84, 50, 4),
	(85, 50, 5),
	(86, 51, 1),
	(87, 51, 2),
	(88, 51, 3),
	(89, 51, 4),
	(90, 51, 5),
	(91, 52, 1),
	(92, 52, 2),
	(93, 52, 3),
	(94, 52, 4),
	(95, 52, 5),
	(96, 53, 1),
	(97, 53, 2),
	(98, 53, 3),
	(99, 53, 4),
	(100, 53, 5),
	(101, 54, 1),
	(102, 54, 2),
	(103, 54, 3),
	(104, 54, 4),
	(105, 54, 5),
	(106, 55, 1),
	(107, 55, 2),
	(108, 55, 3),
	(109, 55, 4),
	(110, 55, 5),
	(111, 56, 1),
	(112, 56, 2),
	(113, 56, 3),
	(114, 56, 4),
	(115, 56, 5),
	(116, 57, 1),
	(117, 57, 2),
	(118, 57, 3),
	(119, 57, 4),
	(120, 57, 5),
	(121, 58, 1),
	(122, 58, 2),
	(123, 58, 3),
	(124, 58, 4),
	(125, 58, 5),
	(126, 59, 1),
	(127, 59, 2),
	(128, 59, 3),
	(129, 59, 4),
	(130, 59, 5),
	(131, 60, 1),
	(132, 60, 2),
	(133, 60, 3),
	(134, 60, 4),
	(135, 60, 5),
	(136, 61, 1),
	(137, 61, 2),
	(138, 61, 3),
	(139, 61, 4),
	(140, 61, 5),
	(141, 62, 1),
	(142, 62, 2),
	(143, 62, 3),
	(144, 62, 4),
	(145, 62, 5),
	(146, 63, 1),
	(147, 63, 2),
	(148, 63, 3),
	(149, 63, 4),
	(150, 63, 5),
	(151, 64, 1),
	(152, 64, 2),
	(153, 64, 3),
	(154, 64, 4),
	(155, 64, 5),
	(156, 65, 1),
	(157, 65, 2),
	(158, 65, 3),
	(159, 65, 4),
	(160, 65, 5),
	(161, 66, 1),
	(162, 66, 2),
	(163, 66, 3),
	(164, 66, 4),
	(165, 66, 5),
	(166, 67, 1),
	(167, 67, 2),
	(168, 67, 3),
	(169, 67, 4),
	(170, 67, 5),
	(171, 68, 1),
	(172, 68, 2),
	(173, 68, 3),
	(174, 68, 4),
	(175, 68, 5),
	(176, 69, 1),
	(177, 69, 2),
	(178, 69, 3),
	(179, 69, 4),
	(180, 69, 5),
	(181, 70, 1),
	(182, 70, 2),
	(183, 70, 3),
	(184, 70, 4),
	(185, 70, 5),
	(186, 71, 1),
	(187, 71, 2),
	(188, 71, 3),
	(189, 71, 4),
	(190, 71, 5),
	(191, 26, 1),
	(192, 26, 2),
	(193, 26, 3),
	(194, 26, 4),
	(195, 26, 5),
	(196, 27, 1),
	(197, 27, 2),
	(198, 27, 3),
	(199, 27, 4),
	(200, 27, 5),
	(201, 28, 1),
	(202, 28, 2),
	(203, 28, 3),
	(204, 28, 4),
	(205, 28, 5),
	(206, 29, 1),
	(207, 29, 2),
	(208, 29, 3),
	(209, 29, 4),
	(210, 29, 5),
	(211, 30, 1),
	(212, 30, 2),
	(213, 30, 3),
	(214, 30, 4),
	(215, 30, 5),
	(216, 31, 1),
	(217, 31, 2),
	(218, 31, 3),
	(219, 31, 4),
	(220, 31, 5),
	(221, 32, 1),
	(222, 32, 2),
	(223, 32, 3),
	(224, 32, 4),
	(225, 32, 5),
	(226, 33, 1),
	(227, 33, 2),
	(228, 33, 3),
	(229, 33, 4),
	(230, 33, 5);

-- Dumping structure for table austingastropub.tbl_orderaddons
CREATE TABLE IF NOT EXISTS `tbl_orderaddons` (
  `orderAddonID` int(11) NOT NULL AUTO_INCREMENT,
  `addonID` int(11) DEFAULT NULL,
  `orderItemID` int(11) DEFAULT NULL,
  PRIMARY KEY (`orderAddonID`),
  KEY `FK_tbl_orderaddons_tbl_addons` (`addonID`),
  KEY `FK_tbl_orderaddons_tbl_orderitems` (`orderItemID`),
  CONSTRAINT `FK_tbl_orderaddons_tbl_addons` FOREIGN KEY (`addonID`) REFERENCES `tbl_addons` (`addonID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_tbl_orderaddons_tbl_orderitems` FOREIGN KEY (`orderItemID`) REFERENCES `tbl_orderitems` (`orderItemID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_orderaddons: ~6 rows (approximately)
INSERT INTO `tbl_orderaddons` (`orderAddonID`, `addonID`, `orderItemID`) VALUES
	(85, 2, 93),
	(86, 1, 93),
	(87, 2, 94),
	(88, 5, 94),
	(89, 2, 96),
	(90, 1, 96),
	(91, 2, 97);

-- Dumping structure for table austingastropub.tbl_orderitems
CREATE TABLE IF NOT EXISTS `tbl_orderitems` (
  `orderItemID` int(11) NOT NULL AUTO_INCREMENT,
  `orderNumber` int(11) DEFAULT NULL,
  `salesOrderNumber` int(11) DEFAULT NULL,
  `productID` int(11) DEFAULT NULL,
  `variationID` int(11) DEFAULT NULL,
  `productQuantity` int(11) DEFAULT NULL,
  `productTotal` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`orderItemID`),
  KEY `FK_tbl_orderitems_tbl_orders` (`orderNumber`),
  KEY `FK_tbl_orderitems_tbl_menu` (`productID`),
  KEY `FK_tbl_orderitems_tbl_variations` (`variationID`),
  KEY `FK_tbl_orderitems_tbl_orders_2` (`salesOrderNumber`),
  CONSTRAINT `FK_tbl_orderitems_tbl_menu` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_orderitems_tbl_orders` FOREIGN KEY (`orderNumber`) REFERENCES `tbl_orders` (`orderNumber`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tbl_orderitems_tbl_orders_2` FOREIGN KEY (`salesOrderNumber`) REFERENCES `tbl_orders` (`salesOrderNumber`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_orderitems_tbl_variations` FOREIGN KEY (`variationID`) REFERENCES `tbl_variations` (`variationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_orderitems: ~8 rows (approximately)
INSERT INTO `tbl_orderitems` (`orderItemID`, `orderNumber`, `salesOrderNumber`, `productID`, `variationID`, `productQuantity`, `productTotal`) VALUES
	(90, 1003, 10003, 80, NULL, 1, 330.00),
	(91, 1004, 10004, 112, NULL, 1, 100.00),
	(92, 1004, 10004, 94, NULL, 1, 260.00),
	(93, 1005, 10005, 69, NULL, 2, 510.00),
	(94, 1006, 10006, 34, 12, 2, 330.00),
	(95, 1007, 10007, 87, NULL, 1, 230.00),
	(96, 1001, 10008, 34, 43, 2, 420.00),
	(97, 1002, 10009, 37, 48, 1, 220.00),
	(98, 1003, 10010, 188, NULL, 1, 440.00);

-- Dumping structure for table austingastropub.tbl_orders
CREATE TABLE IF NOT EXISTS `tbl_orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `orderNumber` int(11) DEFAULT NULL,
  `orderDate` date DEFAULT NULL,
  `orderTime` time DEFAULT NULL,
  `orderClass` enum('Dine In','Take Out') DEFAULT NULL,
  `orderStatus` enum('IN PROCESS','PICKUP','DONE') DEFAULT NULL,
  `salesOrderNumber` int(11) DEFAULT NULL,
  `employeeID` varchar(9) DEFAULT NULL,
  `customerName` varchar(20) DEFAULT NULL,
  `subTotal` decimal(13,2) DEFAULT NULL,
  `totalAmount` decimal(13,2) DEFAULT NULL,
  `amountPaid` decimal(13,2) DEFAULT NULL,
  `discountCardID` varchar(16) DEFAULT NULL,
  `paymentMode` varchar(10) DEFAULT NULL,
  `payReferenceNumber` varchar(13) DEFAULT NULL,
  `additionalNotes` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`orderID`),
  KEY `FK_tbl_orders_employees` (`employeeID`),
  KEY `orderNumber` (`orderNumber`),
  KEY `salesOrderNumber` (`salesOrderNumber`),
  CONSTRAINT `FK_tbl_orders_employees` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`Employee_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_orders: ~8 rows (approximately)
INSERT INTO `tbl_orders` (`orderID`, `orderNumber`, `orderDate`, `orderTime`, `orderClass`, `orderStatus`, `salesOrderNumber`, `employeeID`, `customerName`, `subTotal`, `totalAmount`, `amountPaid`, `discountCardID`, `paymentMode`, `payReferenceNumber`, `additionalNotes`) VALUES
	(70, 1003, '2025-04-21', '17:51:22', 'Take Out', 'DONE', 10003, '123456789', 'Daryl', NULL, 330.00, 400.00, NULL, 'Cash', NULL, ''),
	(71, 1004, '2025-04-21', '17:55:41', 'Dine In', 'DONE', 10004, '123456789', 'Emma', NULL, 360.00, 400.00, NULL, 'Cash', NULL, ''),
	(72, 1005, '2025-04-21', '17:56:05', 'Dine In', 'DONE', 10005, '123456789', 'John', NULL, 510.00, 600.00, NULL, 'Cash', NULL, ''),
	(73, 1006, '2025-04-21', '21:35:52', 'Dine In', 'PICKUP', 10006, '123456789', '', NULL, 264.00, 0.00, NULL, 'Cash', NULL, ''),
	(74, 1007, '2025-04-21', '23:40:47', 'Dine In', 'PICKUP', 10007, '123456789', 'Ry', 230.00, 230.00, 240.00, NULL, 'Cash', NULL, ''),
	(75, 1001, '2025-04-22', '10:47:37', 'Take Out', 'IN PROCESS', 10008, '123456789', 'Ry', 420.00, 336.00, 336.00, '20202020', 'Cash', NULL, ''),
	(76, 1002, '2025-04-22', '10:50:42', 'Dine In', 'IN PROCESS', 10009, '123456789', 'Jy', 220.00, 176.00, 176.00, '12121212', 'PayMaya', '202020202020', ''),
	(77, 1003, '2025-04-22', '11:13:08', 'Take Out', 'IN PROCESS', 10010, '123456789', 'Owen', 440.00, 440.00, 440.00, NULL, 'PayMaya', '123456789012', '');

-- Dumping structure for table austingastropub.tbl_variations
CREATE TABLE IF NOT EXISTS `tbl_variations` (
  `variationID` int(11) NOT NULL AUTO_INCREMENT,
  `variationName` varchar(15) DEFAULT NULL,
  `variationPrice` decimal(13,2) DEFAULT NULL,
  `productID` int(11) DEFAULT NULL,
  PRIMARY KEY (`variationID`),
  KEY `FK_tbl_variations_tbl_menu` (`productID`),
  CONSTRAINT `FK_tbl_variations_tbl_menu` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_variations: ~41 rows (approximately)
INSERT INTO `tbl_variations` (`variationID`, `variationName`, `variationPrice`, `productID`) VALUES
	(1, '8oz', 120.00, 26),
	(2, '12oz', 140.00, 26),
	(3, '8oz', 140.00, 27),
	(4, '12oz', 160.00, 27),
	(5, '8oz', 150.00, 28),
	(6, '12oz', 170.00, 28),
	(7, '8oz', 150.00, 29),
	(8, '12oz', 170.00, 29),
	(9, '8oz', 150.00, 30),
	(10, '12oz', 170.00, 30),
	(11, '8oz', 150.00, 31),
	(12, '12oz', 170.00, 31),
	(13, '8oz', 150.00, 32),
	(14, '12oz', 170.00, 32),
	(15, '8oz', 150.00, 33),
	(16, '12oz', 170.00, 33),
	(42, '12oz', 120.00, 34),
	(43, '16oz', 140.00, 34),
	(44, '12oz', 140.00, 35),
	(45, '16oz', 160.00, 35),
	(46, '12oz', 150.00, 36),
	(47, '16oz', 170.00, 36),
	(48, '16oz', 190.00, 37),
	(49, '12oz', 160.00, 38),
	(50, '16oz', 180.00, 38),
	(51, '12oz', 160.00, 39),
	(52, '16oz', 180.00, 39),
	(53, '12oz', 160.00, 40),
	(54, '16oz', 180.00, 40),
	(55, '12oz', 160.00, 41),
	(56, '16oz', 180.00, 41),
	(57, '12oz', 160.00, 42),
	(58, '16oz', 180.00, 42),
	(59, '12oz', 160.00, 43),
	(60, '16oz', 180.00, 43),
	(61, '12oz', 160.00, 44),
	(62, '16oz', 180.00, 44),
	(63, '12oz', 160.00, 45),
	(64, '16oz', 180.00, 45),
	(65, '12oz', 160.00, 46),
	(66, '16oz', 180.00, 46);

-- Inventory Tables


CREATE TABLE `tbl_item` (
	`Item_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Item_Name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`Item_Image` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`Item_Category` INT(11) NOT NULL,
	`Unit_ID` INT(11) NULL DEFAULT NULL,
	`Item_Lowstock` int(11) NOT NULL,
	PRIMARY KEY (`Item_ID`) USING BTREE,
	INDEX `FK__tbl_itemcategories` (`Item_Category`) USING BTREE,
	INDEX `FK_tbl_item_tbl_unitofmeasurments` (`Unit_ID`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=10
;

CREATE TABLE `tbl_itemcategories` (
	`Category_ID` INT(11) NOT NULL,
	`Category_Name` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`Category_Icon` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`Category_ID`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (1, 'Fruits', 'fi fi-rr-apple-whole');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (2, 'Vegetables', 'fi fi-rr-carrot');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (3, 'Grains', 'fi fi-rr-bread-slice');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (4, 'Dairy', 'fi fi-rr-milk-alt');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (5, 'Fats & Oils', 'fi fi-rr-oil-can');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (6, 'Beverages', 'fi fi-rr-coffee');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (7, 'Sweeteners & Condiments', 'fi fi-rr-salt-shaker');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (8, 'Herbs & Spices', 'fi fi-rr-pepper-hot');
INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES (9, 'Protein', 'fi fi-rr-egg-fried');


CREATE TABLE `tbl_record` (
	`Record_ID` INT(11) NOT NULL,
	`Item_ID` INT(11) NULL DEFAULT NULL,
	`Record_ItemVolume` INT(11) NULL DEFAULT NULL,
	`Record_ItemQuantity` INT(11) NULL DEFAULT NULL,
	`Record_ItemPrice` INT(11) NULL DEFAULT NULL,
	`Record_ItemExpirationDate` DATE NULL DEFAULT NULL,
	`Record_ItemPurchaseDate` DATE NULL DEFAULT NULL,
	`Record_ItemSupplier` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`Record_EmployeeAssigned` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`Record_TotalPrice` int(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Record_ID`) USING BTREE,
	INDEX `FK__tbl_item` (`Item_ID`) USING BTREE,
	INDEX `FK__employees` (`Record_EmployeeAssigned`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `tbl_stocks` (
  `Stock_ID` int(32) NOT NULL,
  `Total_Stock_Budget` int(32) NOT NULL,
  `Total_Expenses` int(32) NOT NULL,
  `Total_Calculated_Budget` int(11) NOT NULL,
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


CREATE TABLE `tbl_unitofmeasurments` (
	`Unit_ID` INT(11) NOT NULL,
	`Unit_Name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`Unit_Acronym` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`Unit_ID`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (2, 'Liters', 'L');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (3, 'Gallon', 'Gal');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (4, 'Carton', 'C');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (5, 'Grams', 'g');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (6, 'Pound', 'lb');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (7, 'Kilogram', 'kg');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (8, 'Sack', 's');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (9, 'Pieces', 'p');
INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES (10, 'Milliliters', 'ml');


CREATE TABLE `tbl_userlogs` (
	`logID` INT(11) NOT NULL,
	`logEmail` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`logRole` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
	`logContent` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`logDate` DATETIME NOT NULL
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `tbl_inventory_changes` (
	`Change_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Record_ID` INT(11) NOT NULL,
	`Change_Quantity` INT(11) NOT NULL,
	`Change_Type` ENUM('decrease','increase') NOT NULL COLLATE 'utf8mb4_general_ci',
	`Change_Date` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`Change_ID`) USING BTREE,
	INDEX `Record_ID` (`Record_ID`) USING BTREE,
	CONSTRAINT `tbl_inventory_changes_ibfk_1` FOREIGN KEY (`Record_ID`) REFERENCES `tbl_record` (`Record_ID`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=14
;


CREATE TABLE `tbl_inventorylogs` (
	`inventoryLogs_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Employee_ID` VARCHAR(9) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`Amount_Added` INT(11) NULL DEFAULT NULL,
	`Date_Time` DATETIME NULL DEFAULT NULL,
	`Previous_Sum` INT(11) NULL DEFAULT NULL,
	`Stock_ID` INT(11) NULL DEFAULT NULL,
 	`Updated_Sum` int(11) DEFAULT NULL,
	PRIMARY KEY (`inventoryLogs_ID`) USING BTREE,
	INDEX `FK__employees` (`Employee_ID`) USING BTREE,
	CONSTRAINT `FK__employees` FOREIGN KEY (`Employee_ID`) REFERENCES `employees` (`Employee_ID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `tbl_record_duplicate` (
  `RecordDuplicate_ID` int(11) NOT NULL,
  `Item_ID` int(11) DEFAULT NULL,
  `Record_ItemVolume` int(11) DEFAULT NULL,
  `Record_ItemQuantity` int(11) DEFAULT NULL,
  `Record_ItemPrice` int(11) DEFAULT NULL,
  `Record_ItemExpirationDate` date DEFAULT NULL,
  `Record_ItemPurchaseDate` date DEFAULT NULL,
  `Record_ItemSupplier` varchar(255) DEFAULT NULL,
  `Record_EmployeeAssigned` varchar(50) DEFAULT NULL,
  `Record_TotalPrice` int(11) DEFAULT NULL
) 
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `tbl_record_duplicate`
  ADD PRIMARY KEY (`RecordDuplicate_ID`);
COMMIT;



/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
