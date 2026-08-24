-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 04:42 PM
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
(1001, 'Amit', 'Scientist B', 'AI &  TNCS', '$2y$10$JfJybCPk1qWDe4Tf5wDf.ODxxB5RNuPzSTdCa8GPkyy/YZX/7kdpO'),
(1002, 'Ansh', 'Scientist B', 'AI Techniques', '$2y$10$ye6Dk0Q2xZKMkSa7QmNyaetew7bT1FqBi9VMz7MSjVn99BkUilm72'),
(1003, 'Sarita', 'Scientist C', 'Data Science & Analytics', '$2y$10$lCD3ZXuwQomS/ArvUoeqL.YTZECgPb.NemKZbkHYwlAgwLtmNIDsK'),
(1004, 'Rahul', 'Scientist C', 'Cyber Security', '$2y$10$aaewuA09EiPeSFQnkXBfoui8Q7d2DGw3cB7DaZzaw.fBHW/jUf0vu'),
(1005, 'Khushi', 'Scientist A', 'Data Science & Analytics', '$2y$10$pGUZrkq5X8H54e25RbI2M.iRnXjpkD1IjyDdAVJZAIlFHS4QuNnha'),
(1006, 'Pallavi', 'Scientist B', 'Advanced Computing', '$2y$10$QFKqUSIH0a53xs/RJWrX8OJto6I3WaK.IVv4DINx3Zit8JGluzRjW'),
(1007, 'Anjali', 'Scientist A', 'Cyber Security', '$2y$10$Cq8X/0/ACAjxJPJXsIuspefRTnUihWoJnP/l00eGQm5EtntnxdFZm'),
(1008, 'Neha', 'Scientist D', 'Advanced Computing', '$2y$10$zviz9z5rsTw.u8PzDKUqJuoNyvyTv2SGnP.YezNUXoAIOGtZZcZSG'),
(1009, 'Udit', 'Scientist B', 'Robotics & Automation', '$2y$10$tdwyBXCoP3pRfMwYCdEEle4JU9U6iYsS0TKj1ZcJioNvmlXKedJlO'),
(1010, 'Ayushi', 'Scientist E', 'Cyber Security', '$2y$10$gzqaIlLKtNywN75m7jXad.l.pzU8aab.GF2zXsGOI5LE7gIEHY2XO'),
(1011, 'Sumit', 'Scientist C', 'AI Techniques', '$2y$10$s36bEV7.jlW7EFF8kVYQSeyCwMmHHlTdJy9T/DalE6HCgHhBaHbJC'),
(1012, 'Vidur', 'Scientist A', 'AI &  TNCS', '$2y$10$lhQWraY0nFwZWu3blMtQJemz4/A/dGmiWfgPwcOfPhQNgs3z3srlK'),
(1013, 'Vaishnavi', 'Scientist B', 'Robotics & Automation', '$2y$10$OdSP6gSgwEURPf3eAEPGe.rGitzooohZr4H700nVVD3r9Q/94Xft6'),
(1014, 'Aman', 'Scientist C', 'Advanced Computing', '$2y$10$9n4k9UMgjVJYhtqJ50zm5.rhAOAiMZvns1wMslQo1zw7g7PSHZcoW'),
(1015, 'Vinay', 'Scientist E', 'AI Techniques', '$2y$10$Xoy7CsjIYLILgqLcFMNSJ.zR2idNWh5265wdTyqLdbk0AlFwUA4rK'),
(1016, 'Vishal', 'Scientist B', 'Advanced Computing', '$2y$10$/S0cvq.EdSNyiGbgENBgm.ZDWX3IyWjn3PhJItWqaUwN5zzpWuhc2'),
(1017, 'Janvi', 'Scientist C', 'AI Techniques', '$2y$10$PGNWDxqsNjE6GpFhNFwVbe4j2YBykxJM.6RbwmP6HjgXuSxpAgZ3K'),
(1018, 'Pallavi', 'Scientist B', 'AI Techniques', '$2y$10$.pi01iXSSseMDdTkYrdnr..2AyLpnJhoLb3WBcqkqUOmNaXKlUq6C'),
(1019, 'Amit', 'Scientist B', 'Cyber Security', '$2y$10$n0vqUILsRoWz40M51S5PFuivg/aSv1YxVnV4K32.uvYpN3xF.JvLW'),
(1020, 'Pallaviiiii', 'Scientist C', 'Robotics & Automation', '$2y$10$RA1US0TPT4uXjul.DAuIPuz1YFWufVFM8vmI8zdJBpelQe5oXX5vK'),
(1021, 'Nisha', 'Scientist A', 'Advanced Computing', '$2y$10$nkbagzm1EhL2P/8ykJxBgerLW/2La4uCf/gh1i1inJEhA60kEa5aK');

-- --------------------------------------------------------

--
-- Table structure for table `karyashala_records`
--

CREATE TABLE `karyashala_records` (
  `id` int(11) NOT NULL,
  `ic_no` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `starting_date` date NOT NULL,
  `block` varchar(20) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyashala_records`
--

INSERT INTO `karyashala_records` (`id`, `ic_no`, `employee_name`, `starting_date`, `block`, `remarks`) VALUES
(1, 1005, 'Khushi', '2024-09-22', '2023-2025', 'complete'),
(2, 1006, 'Pallavi', '2026-02-08', '2025-2027', 'complete'),
(3, 1007, 'Anjali', '2026-11-23', '2025-2027', 'pending'),
(4, 1008, 'Neha', '2023-02-12', '2023-2025', 'complete'),
(5, 1009, 'Udit', '2028-02-23', '2027-2029', 'pending'),
(6, 1010, 'Ayushi', '2027-11-23', '2027-2029', 'pending'),
(7, 1012, 'Vidur', '2028-09-15', '2027-2029', ''),
(8, 1008, 'Neha', '2025-10-22', '2025-2027', ''),
(9, 1012, 'Vidur', '2028-08-23', '2027-2029', '');

-- --------------------------------------------------------

--
-- Table structure for table `module_attendance`
--

CREATE TABLE `module_attendance` (
  `id` int(11) NOT NULL,
  `ic_no` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `block` varchar(20) NOT NULL,
  `attendance` enum('Present','Not Present') NOT NULL DEFAULT 'Not Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module_attendance`
--

INSERT INTO `module_attendance` (`id`, `ic_no`, `employee_name`, `designation`, `block`, `attendance`) VALUES
(1, 1004, 'Rahul', 'Scientist C', '2023-2025', 'Present'),
(2, 1006, 'Pallavi', 'Scientist B', '2025-2027', 'Present'),
(3, 1008, 'Neha', 'Scientist D', '2023-2025', 'Present'),
(4, 1009, 'Udit', 'Scientist B', '2027-2029', 'Present'),
(5, 1005, 'Khushi', 'Scientist A', '2023-2025', 'Present'),
(6, 1010, 'Ayushi', 'Scientist E', '2027-2029', 'Present'),
(7, 1012, 'Vidur', 'Scientist A', '2027-2029', 'Not Present'),
(8, 1007, 'Anjali', 'Scientist A', '2025-2027', 'Not Present');

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
(1002, 'Anshi', 'Admin'),
(1003, 'Sarita', 'Admin'),
(1004, 'Rahul', 'Admin'),
(1005, 'Khushi', 'Karyashala'),
(1006, 'Pallavi', 'Karyashala'),
(1007, 'Anjali', 'Karyashala'),
(1008, 'Neha', 'Karyashala'),
(1009, 'Udit', 'Karyashala'),
(1010, 'Ayushi', 'Karyashala'),
(1011, 'Sumit', 'Karyashala'),
(1012, 'Vidur', 'Karyashala'),
(1013, 'Vaishnavi', 'Karyashala'),
(1014, 'Aman', 'Karyashala');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_attendance`
--
ALTER TABLE `module_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ic_no_block` (`ic_no`,`block`);

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
  MODIFY `ic_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1022;

--
-- AUTO_INCREMENT for table `karyashala_records`
--
ALTER TABLE `karyashala_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `module_attendance`
--
ALTER TABLE `module_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
