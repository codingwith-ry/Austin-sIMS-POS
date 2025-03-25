-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 06:32 AM
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
  `Employee_PhoneNumber` bigint(11) NOT NULL,
  `Employee_Status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`Employee_ID`, `Employee_Role`, `Employee_Name`, `Employee_Email`, `Employee_PassKey`, `Employee_PhoneNumber`, `Employee_Status`) VALUES
('123456789', 'POS Staff Management', 'Dominic Xandy Adino', 'dominicadino23@gmail.com', '123456', 9257717724, 'Active'),
('395635613', 'Inventory Staff Management', 'Patrick Star', 'patrickstar@gmail.com', '654321', 9257717724, 'Active'),
('598353747', 'Administrator', 'John Doe', 'admin@gmail.com', '123456', 9257717724, 'Active'),
('987654321', 'Employee ', 'Spongebob ', 'dominicxandy.adino.cics@ust.edu.ph', '123456', 9257717724, 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_addons`
--

CREATE TABLE IF NOT EXISTS `tbl_addons` (
  `addonID` int(11) NOT NULL AUTO_INCREMENT,
  `addonName` varchar(25) NOT NULL,
  `addonPrice` decimal(10,2) NOT NULL,
  `menuID` int(11) NOT NULL,
  PRIMARY KEY (`addonID`),
  KEY `FK_tbl_addons_tbl_menuclass` (`menuID`),
  CONSTRAINT `FK_tbl_addons_tbl_menuclass` FOREIGN KEY (`menuID`) REFERENCES `tbl_menuclass` (`menuID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `tbl_addons`
--

INSERT INTO `tbl_addons` (`addonID`, `addonName`, `addonPrice`, `menuID`) VALUES
	(1, 'Espresso Shot', 40.00, 1),
	(2, 'Whip Cream', 30.00, 1),
	(3, 'Oatmilk', 40.00, 1),
	(4, 'Sauces', 20.00, 1),
	(5, 'Syrup', 15.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(40) NOT NULL,
  `categoryIcon` varchar(50) NOT NULL,
  `menuID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_categories`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory`
--

CREATE TABLE `tbl_inventory` (
  `Inventory_ID` int(11) NOT NULL,
  `Record_ID` int(11) DEFAULT NULL,
  `Inventory_Quantity` int(11) DEFAULT NULL,
  `Item_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `Item_ID` int(11) NOT NULL,
  `Item_Name` varchar(50) NOT NULL,
  `Item_Image` varchar(50) NOT NULL,
  `Item_Category` int(11) NOT NULL,
  `Unit_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`Item_ID`, `Item_Name`, `Item_Image`, `Item_Category`, `Unit_ID`) VALUES
(3, 'Stawberry', 'itemImages/strawberry.webp', 1, 5),
(4, 'Bounty Chicken Breast Fillet', 'itemImages/bounty chicken breast.webp', 9, 5),
(5, 'Nestle Bear Brand', 'itemImages/bearbrand.jpg', 4, 5),
(6, 'Coke', 'itemImages/coke.webp', 6, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_itemcategories`
--

CREATE TABLE `tbl_itemcategories` (
  `Category_ID` int(11) NOT NULL,
  `Category_Name` varchar(150) DEFAULT NULL,
  `Category_Icon` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_itemcategories`
--

INSERT INTO `tbl_itemcategories` (`Category_ID`, `Category_Name`, `Category_Icon`) VALUES
(1, 'Fruits', 'fi fi-rr-apple-whole'),
(2, 'Vegetables', 'fi fi-rr-carrot'),
(3, 'Grains', 'fi fi-rr-bread-slice'),
(4, 'Dairy', 'fi fi-rr-milk-alt'),
(5, 'Fats & Oils', 'fi fi-rr-oil-can'),
(6, 'Beverages', 'fi fi-rr-coffee'),
(7, 'Sweeteners & Condiments', 'fi fi-rr-salt-shaker'),
(8, 'Herbs & Spices', 'fi fi-rr-pepper-hot'),
(9, 'Protein', 'fi fi-rr-egg-fried');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `productID` int(11) NOT NULL,
  `productName` varchar(50) NOT NULL,
  `productImage` varchar(75) DEFAULT NULL,
  `menuID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  `productPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_menu`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menuclass`
--

CREATE TABLE `tbl_menuclass` (
  `menuID` int(11) NOT NULL,
  `menuName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_menuclass`
--

INSERT INTO `tbl_menuclass` (`menuID`, `menuName`) VALUES
(1, 'Coffee Menu'),
(2, 'Gastro Pub Menu'),
(3, 'Party Tray Menu');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menutoaddons`
--

CREATE TABLE `tbl_menutoaddons` (
  `menuAddonID` int(11) NOT NULL,
  `productID` int(11) DEFAULT NULL,
  `addonID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_menutoaddons`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orderitems`
--

CREATE TABLE `tbl_orderitems` (
  `orderItemID` int(11) NOT NULL,
  `orderID` int(11) DEFAULT NULL,
  `productID` int(11) DEFAULT NULL,
  `productQuantity` int(11) NOT NULL,
  `productTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orderitems`
--

INSERT INTO `tbl_orderitems` (`orderItemID`, `orderID`, `productID`, `productQuantity`, `productTotal`) VALUES
(1, 1, 1, 3, 0.00),
(2, 2, 2, 7, 0.00),
(3, 3, 3, 12, 0.00),
(4, 4, 4, 15, 0.00),
(5, 5, 5, 25, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `orderID` int(11) NOT NULL,
  `orderNumber` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderTime` time NOT NULL,
  `orderClass` varchar(8) NOT NULL,
  `orderStatus` enum('IN PROCESS','DONE','CANCELLED') NOT NULL,
  `salesOrderNumber` varchar(50) DEFAULT NULL,
  `employeeID` varchar(9) NOT NULL,
  `orderRemarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`orderID`, `orderNumber`, `orderDate`, `orderTime`, `orderClass`, `orderStatus`, `salesOrderNumber`, `employeeID`, `orderRemarks`) VALUES
(1, 1002, '2025-03-02', '10:15:00', 'DINE IN', 'IN PROCESS', 'ORD-0001', '123456789', 'Please prepare the order as soon as possible.'),
(2, 1003, '2025-03-02', '12:45:00', 'DINE IN', 'IN PROCESS', 'ORD-0002', '123456789', 'Please prepare the order as soon as possible.'),
(3, 1004, '2025-03-02', '18:30:00', 'TAKE OUT', 'IN PROCESS', 'ORD-0003', '123456789', 'Please prepare the order as soon as possible.'),
(4, 1005, '2025-03-02', '20:00:00', 'TAKE OUT', 'IN PROCESS', 'ORD-0004', '123456789', 'Please prepare the order as soon as possible.'),
(5, 1006, '2025-03-02', '21:10:00', 'DINE IN', 'IN PROCESS', 'ORD-0005', '123456789', 'Please prepare the order as soon as possible.');

--
-- Triggers `tbl_orders`
--
-- --------------------------------------------------------

--
-- Table structure for table `tbl_record`
--

CREATE TABLE `tbl_record` (
  `Record_ID` int(11) NOT NULL,
  `Item_ID` int(11) DEFAULT NULL,
  `Record_ItemVolume` int(11) DEFAULT NULL,
  `Record_ItemQuantity` int(11) DEFAULT NULL,
  `Record_ItemPrice` int(11) DEFAULT NULL,
  `Record_ItemExpirationDate` date DEFAULT NULL,
  `Record_ItemPurchaseDate` date DEFAULT NULL,
  `Record_ItemSupplier` varchar(255) DEFAULT NULL,
  `Record_EmployeeAssigned` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_record`
--

INSERT INTO `tbl_record` (`Record_ID`, `Item_ID`, `Record_ItemVolume`, `Record_ItemQuantity`, `Record_ItemPrice`, `Record_ItemExpirationDate`, `Record_ItemPurchaseDate`, `Record_ItemSupplier`, `Record_EmployeeAssigned`) VALUES
(239507, 6, 355, 20, 150, '2025-03-27', '2025-03-20', 'Coca-cola', '123456789'),
(593638, 4, 180, 5, 205, '2028-07-21', '2025-03-21', 'Bounty Fresh', '987654321'),
(700457, 3, 10, 10, 350, '2029-07-26', '2025-03-19', 'N/A', '395635613'),
(822860, 3, 10, 6, 50, '2027-03-21', '2025-03-21', 'N/A', '123456789'),
(962088, 5, 1200, 15, 350, '2028-07-06', '2025-03-25', 'Nestle', '123456789');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unitofmeasurments`
--

CREATE TABLE `tbl_unitofmeasurments` (
  `Unit_ID` int(11) NOT NULL,
  `Unit_Name` varchar(50) NOT NULL,
  `Unit_Acronym` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_unitofmeasurments`
--

INSERT INTO `tbl_unitofmeasurments` (`Unit_ID`, `Unit_Name`, `Unit_Acronym`) VALUES
(2, 'Liters', 'L'),
(3, 'Gallon', 'Gal'),
(4, 'Carton', 'C'),
(5, 'Grams', 'g'),
(6, 'Pound', 'lb'),
(7, 'Kilogram', 'kg'),
(8, 'Sack', 's'),
(9, 'Pieces', 'p'),
(10, 'Millimiliters', 'ml');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`Employee_ID`),
  ADD UNIQUE KEY `Employee_Email` (`Employee_Email`);

--
-- Indexes for table `tbl_addons`
--
ALTER TABLE `tbl_addons`
  ADD PRIMARY KEY (`addonID`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`categoryID`),
  ADD KEY `menuID` (`menuID`);

--
-- Indexes for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD PRIMARY KEY (`Inventory_ID`) USING BTREE,
  ADD KEY `FK__tbl_record` (`Record_ID`) USING BTREE,
  ADD KEY `FK_tbl_inventory_tbl_item` (`Item_ID`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`Item_ID`) USING BTREE,
  ADD KEY `FK__tbl_itemcategories` (`Item_Category`) USING BTREE,
  ADD KEY `FK_tbl_item_tbl_unitofmeasurments` (`Unit_ID`);

--
-- Indexes for table `tbl_itemcategories`
--
ALTER TABLE `tbl_itemcategories`
  ADD PRIMARY KEY (`Category_ID`) USING BTREE;

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `menuID` (`menuID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `tbl_menuclass`
--
ALTER TABLE `tbl_menuclass`
  ADD PRIMARY KEY (`menuID`);

--
-- Indexes for table `tbl_menutoaddons`
--
ALTER TABLE `tbl_menutoaddons`
  ADD PRIMARY KEY (`menuAddonID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `addonID` (`addonID`);

--
-- Indexes for table `tbl_orderitems`
--
ALTER TABLE `tbl_orderitems`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`orderID`),
  ADD UNIQUE KEY `orderNumber` (`orderNumber`),
  ADD UNIQUE KEY `salesOrderNumber` (`salesOrderNumber`),
  ADD KEY `fk_employee_id` (`employeeID`);

--
-- Indexes for table `tbl_record`
--
ALTER TABLE `tbl_record`
  ADD PRIMARY KEY (`Record_ID`) USING BTREE,
  ADD KEY `FK__tbl_item` (`Item_ID`) USING BTREE,
  ADD KEY `FK__employees` (`Record_EmployeeAssigned`) USING BTREE;

--
-- Indexes for table `tbl_unitofmeasurments`
--
ALTER TABLE `tbl_unitofmeasurments`
  ADD PRIMARY KEY (`Unit_ID`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_addons`
--
ALTER TABLE `tbl_addons`
  MODIFY `addonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_itemcategories`
--
ALTER TABLE `tbl_itemcategories`
  MODIFY `Category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_menuclass`
--
ALTER TABLE `tbl_menuclass`
  MODIFY `menuID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_menutoaddons`
--
ALTER TABLE `tbl_menutoaddons`
  MODIFY `menuAddonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_orderitems`
--
ALTER TABLE `tbl_orderitems`
  MODIFY `orderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_unitofmeasurments`
--
ALTER TABLE `tbl_unitofmeasurments`
  MODIFY `Unit_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD CONSTRAINT `tbl_categories_ibfk_1` FOREIGN KEY (`menuID`) REFERENCES `tbl_menuclass` (`menuID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD CONSTRAINT `FK__tbl_record` FOREIGN KEY (`Record_ID`) REFERENCES `tbl_record` (`Record_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tbl_inventory_tbl_item` FOREIGN KEY (`Item_ID`) REFERENCES `tbl_item` (`Item_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD CONSTRAINT `FK__tbl_itemcategories` FOREIGN KEY (`Item_Category`) REFERENCES `tbl_itemcategories` (`Category_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tbl_item_tbl_unitofmeasurments` FOREIGN KEY (`Unit_ID`) REFERENCES `tbl_unitofmeasurments` (`Unit_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD CONSTRAINT `tbl_menu_ibfk_1` FOREIGN KEY (`menuID`) REFERENCES `tbl_menuclass` (`menuID`) ON DELETE SET NULL,
  ADD CONSTRAINT `tbl_menu_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `tbl_categories` (`categoryID`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_menutoaddons`
--
ALTER TABLE `tbl_menutoaddons`
  ADD CONSTRAINT `tbl_menutoaddons_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_menutoaddons_ibfk_2` FOREIGN KEY (`addonID`) REFERENCES `tbl_addons` (`addonID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_orderitems`
--
ALTER TABLE `tbl_orderitems`
  ADD CONSTRAINT `tbl_orderitems_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `tbl_orders` (`orderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_orderitems_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `tbl_menu` (`productID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `fk_employee_id` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`Employee_ID`);

--
-- Constraints for table `tbl_record`
--
ALTER TABLE `tbl_record`
  ADD CONSTRAINT `FK__employees` FOREIGN KEY (`Record_EmployeeAssigned`) REFERENCES `employees` (`Employee_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK__tbl_item` FOREIGN KEY (`Item_ID`) REFERENCES `tbl_item` (`Item_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
