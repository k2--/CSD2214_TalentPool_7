-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2021 at 11:50 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_job_applications`
--
CREATE DATABASE IF NOT EXISTS `db_job_applications` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_job_applications`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_applicants`
--

DROP TABLE IF EXISTS `tbl_applicants`;
CREATE TABLE `tbl_applicants` (
  `appId` int(11) NOT NULL,
  `jobid` int(11) NOT NULL,
  `firstname` varchar(254) NOT NULL,
  `lastname` varchar(254) NOT NULL,
  `email` varchar(254) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `availability` varchar(5) NOT NULL,
  `resumename` varchar(254) NOT NULL,
  `resumefiletype` varchar(254) NOT NULL,
  `skill1` varchar(50) NOT NULL,
  `skill1year` int(11) NOT NULL,
  `skill2` varchar(50) NOT NULL,
  `skill2year` int(11) NOT NULL,
  `skill3` varchar(50) NOT NULL,
  `skill3year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jobs`
--

DROP TABLE IF EXISTS `tbl_jobs`;
CREATE TABLE `tbl_jobs` (
  `jobid` int(11) NOT NULL,
  `jobtitle` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `jobdescription` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_applicants`
--
ALTER TABLE `tbl_applicants`
  ADD PRIMARY KEY (`appId`);

--
-- Indexes for table `tbl_jobs`
--
ALTER TABLE `tbl_jobs`
  ADD PRIMARY KEY (`jobid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_applicants`
--
ALTER TABLE `tbl_applicants`
  MODIFY `appId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_jobs`
--
ALTER TABLE `tbl_jobs`
  MODIFY `jobid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
