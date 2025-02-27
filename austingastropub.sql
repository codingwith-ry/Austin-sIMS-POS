-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 06:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `austingastropub`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `Employee_ID` varchar(9) NOT NULL,
  `Employee_Role` text NOT NULL,
  `Employee_Name` text NOT NULL,
  `Employee_Email` varchar(255) NOT NULL,
  `Employee_PassKey` varchar(6) NOT NULL,
  `Employee_PhoneNumber` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`Employee_ID`, `Employee_Role`, `Employee_Name`, `Employee_Email`, `Employee_PassKey`, `Employee_PhoneNumber`) VALUES
('123456789', 'Inventory Manager', 'Dominic Xandy Adino', 'dominicadino23@gmail.com', '123456', 9257717724);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`Employee_ID`),
  ADD UNIQUE KEY `Employee_Email` (`Employee_Email`);
COMMIT;


CREATE TABLE IF NOT EXISTS `tbl_addons` (
  `addonID` int(11) NOT NULL AUTO_INCREMENT,
  `addonName` varchar(25) NOT NULL,
  `addonPrice` decimal(10,2) NOT NULL,
  PRIMARY KEY (`addonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_addons: ~0 rows (approximately)

-- Dumping structure for table austingastropub.tbl_categories
CREATE TABLE IF NOT EXISTS `tbl_categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(40) NOT NULL,
  `categoryIcon` varchar(50) NOT NULL,
  `menuID` int(11) DEFAULT NULL,
  PRIMARY KEY (`categoryID`),
  KEY `menuID` (`menuID`),
  CONSTRAINT `menuID` FOREIGN KEY (`menuID`) REFERENCES `tbl_menuclass` (`menuID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_categories: ~25 rows (approximately)
INSERT INTO `tbl_categories` (`categoryID`, `categoryName`, `categoryIcon`, `menuID`) VALUES
	(1, 'Hot', 'fi fi-rr-mug-hot-alt', 1),
	(2, 'Iced', 'fi fi-rr-coffee', 1),
	(3, 'Tea and Refresher', 'fi fi-rr-mug-tea', 1),
	(4, 'Slushies', 'fi fi-rr-smoothie', 1),
	(5, 'Signature Drinks', 'fi fi-rr-sparkles', 1),
	(6, 'Non-Coffee', 'fi fi-rr-milk', 1),
	(7, 'Iced Blended Coffee', 'fi fi-rr-snowflake', 1),
	(8, 'Signature Cocktails', 'fi fi-rs-star', 2),
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
	(29, 'Seafoods', 'fi fi-rr-shrimp', 3);

-- Dumping structure for table austingastropub.tbl_menu
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `productName` varchar(50) NOT NULL,
  `productImage` varchar(75) DEFAULT NULL,
  `menuID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  `productPrice` decimal(10,2) NOT NULL,
  PRIMARY KEY (`productID`),
  KEY `menuID` (`menuID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `tbl_menu_ibfk_1` FOREIGN KEY (`menuID`) REFERENCES `tbl_menuclass` (`menuID`) ON DELETE SET NULL,
  CONSTRAINT `tbl_menu_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `tbl_categories` (`categoryID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_menu: ~5 rows (approximately)
INSERT INTO `tbl_menu` (`productID`, `productName`, `productImage`, `menuID`, `categoryID`, `productPrice`) VALUES
	(1, 'Creamy Beef with Mushroom', '', 3, 26, 2300.00),
	(2, 'Lengua with Mushroom', '', 3, 26, 2800.00),
	(3, 'Spicy Beef Caldereta', '', 3, 26, 2150.00),
	(4, 'Roast Beef with Mashed Potato', '', 3, 26, 2450.00),
	(5, 'Beef Salpicao', '', 3, 26, 2250.00);

-- Dumping structure for table austingastropub.tbl_menuclass
CREATE TABLE IF NOT EXISTS `tbl_menuclass` (
  `menuID` int(11) NOT NULL AUTO_INCREMENT,
  `menuName` varchar(50) NOT NULL,
  PRIMARY KEY (`menuID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_menuclass: ~2 rows (approximately)
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
  KEY `productID` (`productID`),
  KEY `addonID` (`addonID`),
  CONSTRAINT `tbl_menutoaddons_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE CASCADE,
  CONSTRAINT `tbl_menutoaddons_ibfk_2` FOREIGN KEY (`addonID`) REFERENCES `tbl_addons` (`addonID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_menutoaddons: ~0 rows (approximately)

-- Dumping structure for table austingastropub.tbl_orderitems
CREATE TABLE IF NOT EXISTS `tbl_orderitems` (
  `orderItemID` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` int(11) DEFAULT NULL,
  `productID` int(11) DEFAULT NULL,
  `productAddons` text DEFAULT NULL,
  `productQuantity` int(11) NOT NULL,
  `productTotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`orderItemID`),
  KEY `orderID` (`orderID`),
  KEY `productID` (`productID`),
  CONSTRAINT `tbl_orderitems_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `tbl_orders` (`orderID`) ON DELETE CASCADE,
  CONSTRAINT `tbl_orderitems_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table austingastropub.tbl_orderitems: ~0 rows (approximately)

-- Dumping structure for table austingastropub.tbl_orders
CREATE TABLE IF NOT EXISTS `tbl_orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `orderNumber` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderTime` time NOT NULL,
  `orderClass` varchar(8) NOT NULL,
  PRIMARY KEY (`orderID`),
  UNIQUE KEY `orderNumber` (`orderNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
