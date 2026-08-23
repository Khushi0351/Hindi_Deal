-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2026 at 08:42 PM
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
-- Database: `karyashala`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `ic_no` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `emp_group` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`ic_no`, `name`, `designation`, `emp_group`, `password`) VALUES
(1001, 'Amit', 'Scientist A', 'A', '123456'),
(1002, 'Ansh', 'Scientist B', 'B', '123456'),
(1003, 'Sarita', 'Scientist C', 'D', '123456'),
(1004, 'Rahul', 'Scientist C', 'C', '123456'),
(1005, 'Khushi', 'Scientist A', 'A', '123456'),
(1006, 'Pallavi', 'Scientist B', 'A', '123456'),
(1007, 'Anjali', 'Scientist A', 'A', '123456'),
(1008, 'Neha', 'Scientist D', 'D', '123456'),
(1009, 'Udit', 'Scientist B', 'A', '123456'),
(1010, 'Ayushi', 'Scientist E', 'A', '123456'),
(1011, 'Sumit', 'Scientist C', 'B', '123456'),
(1012, 'Vidur', 'Scientist A', 'A', '123456'),
(1013, 'Vaishnavi', 'Scientist B', 'B', '123456'),
(1014, 'Aman', 'Scientist C', 'D', '123456'),
(1015, 'Vinay', 'Scientist E', 'D', '123456'),
(1016, 'Vishal', 'Scientist B', 'C', '123456'),
(1017, 'Zoya', 'Scientist C', 'B', '123456'),
(1018, 'Priya', 'Scientist B', 'D', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `karyashala_records`
--

CREATE TABLE `karyashala_records` (
  `ic_no` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `starting_date` date NOT NULL,
  `block` varchar(20) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyashala_records`
--

INSERT INTO `karyashala_records` (`ic_no`, `employee_name`, `starting_date`, `block`, `remarks`) VALUES
(1004, 'Rahul', '2024-07-23', '2023-2025', 'Training completed'),
(1005, 'Khushi', '2024-09-22', '2023-2025', 'complete'),
(1006, 'Pallavi', '2026-02-08', '2025-2027', 'complete'),
(1007, 'Anjali', '2026-11-23', '2025-2027', 'pending'),
(1008, 'Neha', '2023-02-12', '2023-2025', 'complete'),
(1009, 'Udit', '2028-02-23', '2027-2029', 'pending'),
(1010, 'Ayushi', '2027-11-23', '2027-2029', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `module_attendance`
--

CREATE TABLE `module_attendance` (
  `ic_no` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `block` varchar(20) NOT NULL,
  `attendance` enum('Present','Not Present') NOT NULL DEFAULT 'Not Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module_attendance`
--

INSERT INTO `module_attendance` (`ic_no`, `employee_name`, `designation`, `block`, `attendance`) VALUES
(1004, 'Rahul', 'Scientist C', '2023-2025', 'Present'),
(1006, 'Pallavi', 'Scientist B', '2025-2027', 'Present'),
(1008, 'Neha', 'Scientist D', '2023-2025', 'Present'),
(1009, 'Udit', 'Scientist B', '2027-2029', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `ic_no` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role_name` enum('Admin','Karyashala') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`ic_no`, `name`, `role_name`) VALUES
(1001, 'Amit', 'Admin'),
(1002, 'Ansh', 'Admin'),
(1003, 'Sarita', 'Admin'),
(1004, 'Rahul', 'Karyashala'),
(1005, 'Khushi', 'Karyashala'),
(1006, 'Pallavi', 'Karyashala'),
(1007, 'Anjali', 'Karyashala'),
(1008, 'Neha', 'Karyashala'),
(1009, 'Udit', 'Karyashala'),
(1010, 'Ayushi', 'Karyashala');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`ic_no`);

--
-- Indexes for table `karyashala_records`
--
ALTER TABLE `karyashala_records`
  ADD PRIMARY KEY (`ic_no`);

--
-- Indexes for table `module_attendance`
--
ALTER TABLE `module_attendance`
  ADD PRIMARY KEY (`ic_no`,`block`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ic_no`),
  ADD UNIQUE KEY `ic_no` (`ic_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `ic_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1019;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
