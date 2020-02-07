-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2015 at 10:09 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pickupdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_customer`
--

CREATE TABLE IF NOT EXISTS `master_customer` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `customer_no` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `userkey` varchar(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Table structure for table `master_pickupboy`
--

CREATE TABLE IF NOT EXISTS `master_pickupboy` (
`id` int(11) NOT NULL,
  `pincode` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Table structure for table `master_shipment`
--

CREATE TABLE IF NOT EXISTS `master_shipment` (
`id` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `shipmentno` varchar(35) NOT NULL,
  `pickupid` int(11) NOT NULL DEFAULT '0',
  `customer_no` varchar(15) NOT NULL,
  `userkey` int(35) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Table structure for table `master_vendor`
--

CREATE TABLE IF NOT EXISTS `master_vendor` (
`uid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `pincode` int(11) NOT NULL,
  `customer_no` int(11) NOT NULL,
  `userkey` int(35) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `master_pickupboy` (
`id` int(11) NOT NULL,
  `pincode` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_customer`
--
ALTER TABLE `master_customer`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_pickupboy`
--
ALTER TABLE `master_pickupboy`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_shipment`
--
ALTER TABLE `master_shipment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_vendor`
--
ALTER TABLE `master_vendor`
 ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `master_customer`
--
ALTER TABLE `master_customer`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `master_pickupboy`
--
ALTER TABLE `master_pickupboy`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `master_shipment`
--
ALTER TABLE `master_shipment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `master_vendor`
--
ALTER TABLE `master_vendor`
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
