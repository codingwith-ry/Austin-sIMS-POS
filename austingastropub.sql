-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 11:44 AM
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

CREATE TABLE `tbl_addons` (
  `addonID` int(11) NOT NULL,
  `addonName` varchar(25) NOT NULL,
  `addonPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `tbl_addons`

INSERT INTO `tbl_addons` (`addonID`, `addonName`, `addonPrice`) VALUES
(1, 'Creamy Beef with Mushroom', 80.00),
(2, 'Beef Salpicao', 105.00),
(3, 'Chicken Alfredo', 105.00),
(4, 'Spicy Beef Caldereta', 105.00),
(5, 'Lengua with Mushroom', 105.00);
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
(4, 'Bounty Chicken Breast Fillet', 'itemImages/bounty chicken breast.webp', 9, 5);

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
(5, 'Beef Salpicao', '', 3, 26, 2250.00);

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

-- Dumping data for table `tbl_menutoaddons`
INSERT INTO `tbl_menutoaddons` (`menuAddonID`, `productID`, `addonID`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 2, 2),
(4, 3, 3),
(5, 4, 4),
(6, 5, 5);

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
  `orderRemarks` text DEFAULT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `tbl_orders`
INSERT INTO `tbl_orders` (`orderID`, `orderNumber`, `orderDate`, `orderTime`, `orderClass`, `orderStatus`, `salesOrderNumber`, `employeeID`, `orderRemarks`) VALUES
(1, 1002, '2025-03-02', '10:15:00', 'DINE IN', 'IN PROCESS', 'ORD-0001', '123456789', 'Please prepare the order as soon as possible.'),
(2, 1003, '2025-03-02', '12:45:00', 'DINE IN', 'IN PROCESS', 'ORD-0002', '123456789', 'Please prepare the order as soon as possible.'),
(3, 1004, '2025-03-02', '18:30:00', 'TAKE OUT', 'IN PROCESS', 'ORD-0003', '123456789', 'Please prepare the order as soon as possible.'),
(4, 1005, '2025-03-02', '20:00:00', 'TAKE OUT', 'IN PROCESS', 'ORD-0004', '123456789', 'Please prepare the order as soon as possible.'),
(5, 1006, '2025-03-02', '21:10:00', 'DINE IN', 'IN PROCESS', 'ORD-0005', '123456789', 'Please prepare the order as soon as possible.');

--
-- Define the trigger
DELIMITER $$

CREATE TRIGGER `before_insert_salesOrderNumber` 
BEFORE INSERT ON `tbl_orders` 
FOR EACH ROW 
BEGIN
    DECLARE lastOrderNumber INT DEFAULT 0;
    DECLARE newOrderNumber VARCHAR(50);

    /* Reset orders logic */
    SELECT IFNULL(MAX(CAST(SUBSTRING(salesOrderNumber, 5) AS UNSIGNED)), 0)
    INTO lastOrderNumber
    FROM tbl_orders
    WHERE orderDate = CURDATE();

    SET newOrderNumber = CONCAT('ORD-', LPAD(lastOrderNumber + 1, 4, '0'));

    SET NEW.salesOrderNumber = newOrderNumber;
END
$$

DELIMITER ;

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
(593638, 4, 180, 5, 205, '2028-07-21', '2025-03-21', 'Bounty Fresh', '987654321'),
(822860, 3, 10, 6, 50, '2027-03-21', '2025-03-21', 'N/A', '123456789');

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
(9, 'Pieces', 'p');

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
  MODIFY `addonID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `menuAddonID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `Unit_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
