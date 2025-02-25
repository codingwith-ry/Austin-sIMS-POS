-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 03:58 PM
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
('', 'Employee ', 'Spongebob ', 'dominicxandy.adino.cics@ust.edu.ph', '123456', 9257717724),
('123456789', 'Inventory Manager', 'Dominic Xandy Adino', 'dominicadino23@gmail.com', '123456', 9257717724);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `Item_ID` varchar(9) NOT NULL,
  `Item_Name` varchar(50) NOT NULL,
  `Item_Quantity` int(255) NOT NULL,
  `Date_Purchased` date NOT NULL,
  `Expiration_Date` date NOT NULL,
  `Item_Price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`Item_ID`),
  ADD UNIQUE KEY `Item_Name` (`Item_Name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
