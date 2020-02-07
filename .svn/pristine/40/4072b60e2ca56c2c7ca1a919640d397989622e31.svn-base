-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2012 at 05:04 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `elixiaspeed_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `acalerts`
--

CREATE TABLE IF NOT EXISTS `acalerts` (
  `acalertid` int(11) NOT NULL AUTO_INCREMENT,
  `firstcheck` datetime NOT NULL,
  `last_ignition` tinyint(2) NOT NULL,
  `customerno` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `aci_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`acalertid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ac_sensor`
--

CREATE TABLE IF NOT EXISTS `ac_sensor` (
  `ac_sen_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstcheck` datetime NOT NULL,
  `last_ignition` tinyint(2) NOT NULL,
  `customerno` int(11) NOT NULL,
  `deviceid` int(11) NOT NULL,
  PRIMARY KEY (`ac_sen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `artid` int(11) NOT NULL AUTO_INCREMENT,
  `artname` varchar(100) NOT NULL,
  `maxtemp` float NOT NULL,
  `mintemp` float NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`artid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `articlemanage`
--

CREATE TABLE IF NOT EXISTS `articlemanage` (
  `artmanid` int(11) NOT NULL AUTO_INCREMENT,
  `artid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`artmanid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkpoint`
--

CREATE TABLE IF NOT EXISTS `checkpoint` (
  `checkpointid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `cadd1` varchar(100) NOT NULL,
  `cadd2` varchar(100) NOT NULL,
  `cadd3` varchar(100) NOT NULL,
  `ccity` varchar(50) NOT NULL,
  `cstate` varchar(50) NOT NULL,
  `czip` varchar(50) NOT NULL,
  `cgeolat` float NOT NULL,
  `cgeolong` float NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `crad` float NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`checkpointid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkpointmanage`
--

CREATE TABLE IF NOT EXISTS `checkpointmanage` (
  `cmid` int(11) NOT NULL AUTO_INCREMENT,
  `checkpointid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `conflictstatus` tinyint(1) NOT NULL DEFAULT '1',
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`cmid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkpointreport`
--

CREATE TABLE IF NOT EXISTS `checkpointreport` (
  `chkrepid` int(11) NOT NULL AUTO_INCREMENT,
  `chkid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `customerno` int(11) NOT NULL,
  PRIMARY KEY (`chkrepid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `communicationhistory`
--

CREATE TABLE IF NOT EXISTS `communicationhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queueid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(400) NOT NULL,
  `datecreated` datetime NOT NULL,
  `datesent` datetime NOT NULL,
  `confirmation` tinyint(1) NOT NULL,
  `sent_error` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `communicationqueue`
--

CREATE TABLE IF NOT EXISTS `communicationqueue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(500) NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `customerno` int(11) NOT NULL AUTO_INCREMENT,
  `customername` varchar(100) NOT NULL,
  `customercompany` varchar(100) NOT NULL,
  `customeradd1` varchar(100) NOT NULL,
  `customeradd2` varchar(100) NOT NULL,
  `customercity` varchar(50) NOT NULL,
  `customerstate` varchar(30) NOT NULL,
  `customerzip` varchar(10) NOT NULL,
  `customerphone` varchar(15) NOT NULL,
  `customercell` varchar(15) NOT NULL,
  `customeremail` varchar(50) NOT NULL,
  `dateadded` date NOT NULL,
  `teamid` int(11) NOT NULL,
  `notes` varchar(100) NOT NULL,
  `agreedby` varchar(100) NOT NULL,
  `agreeddate` datetime NOT NULL,
  `aci_time` tinyint(3) NOT NULL,
  PRIMARY KEY (`customerno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dbpatches`
--

CREATE TABLE IF NOT EXISTS `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL,
  PRIMARY KEY (`patchid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `deviceid` bigint(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `devicekey` varchar(200) NOT NULL,
  `devicelat` float NOT NULL,
  `devicelong` float NOT NULL,
  `lastupdated` datetime NOT NULL,
  `registeredon` datetime NOT NULL,
  `contract` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `altitude` varchar(8) NOT NULL,
  `directionchange` varchar(5) NOT NULL,
  `inbatt` varchar(8) NOT NULL,
  `hwv` varchar(5) NOT NULL,
  `swv` varchar(5) NOT NULL,
  `msgid` varchar(11) NOT NULL,
  `uid` varchar(11) NOT NULL,
  `status` varchar(2) NOT NULL,
  `ignition` varchar(2) NOT NULL,
  `powercut` varchar(2) NOT NULL,
  `tamper` varchar(2) NOT NULL,
  `gpsfixed` varchar(2) NOT NULL,
  `online/offline` varchar(2) NOT NULL,
  `gsmstrength` varchar(5) NOT NULL,
  `gsmregister` varchar(2) NOT NULL,
  `gprsregister` varchar(2) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `ignition_last_status` tinyint(1) NOT NULL,
  `ignition_last_check` datetime NOT NULL,
  `ignition_email_status` tinyint(1) NOT NULL,
  `aci_status` tinyint(1) NOT NULL,
  `satv` int(11) NOT NULL,
  PRIMARY KEY (`deviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE IF NOT EXISTS `driver` (
  `driverid` int(11) NOT NULL AUTO_INCREMENT,
  `drivername` varchar(40) NOT NULL,
  `driverlicno` varchar(40) NOT NULL,
  `driverphone` varchar(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`driverid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ecodeman`
--

CREATE TABLE IF NOT EXISTS `ecodeman` (
  `ecodemanid` int(11) NOT NULL AUTO_INCREMENT,
  `ecodeid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`ecodemanid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `elixiacode`
--

CREATE TABLE IF NOT EXISTS `elixiacode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `ecode` int(11) NOT NULL,
  `datecreated` datetime NOT NULL,
  `expirydate` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eventalerts`
--

CREATE TABLE IF NOT EXISTS `eventalerts` (
  `vehicleid` int(11) NOT NULL,
  `overspeed` tinyint(4) NOT NULL,
  `tamper` tinyint(4) NOT NULL,
  `powercut` tinyint(4) NOT NULL,
  `ignition` tinyint(4) NOT NULL,
  `temp` tinyint(4) NOT NULL,
  `ac` tinyint(4) NOT NULL,
  `customerno` int(11) NOT NULL,
  `eaid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`eaid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fence`
--

CREATE TABLE IF NOT EXISTS `fence` (
  `fenceid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `fencename` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`fenceid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fenceman`
--

CREATE TABLE IF NOT EXISTS `fenceman` (
  `fmid` int(11) NOT NULL AUTO_INCREMENT,
  `fenceid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `conflictstatus` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`fmid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `geofence`
--

CREATE TABLE IF NOT EXISTS `geofence` (
  `geofenceid` int(11) NOT NULL AUTO_INCREMENT,
  `fenceid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `geolat` float NOT NULL,
  `geolong` float NOT NULL,
  PRIMARY KEY (`geofenceid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ignitionalert`
--

CREATE TABLE IF NOT EXISTS `ignitionalert` (
  `vehicleid` int(11) NOT NULL,
  `last_status` tinyint(4) NOT NULL,
  `last_check` datetime NOT NULL,
  `count` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `customerno` int(11) NOT NULL,
  `igalertid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`igalertid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reportman`
--

CREATE TABLE IF NOT EXISTS `reportman` (
  `repid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `date` date NOT NULL,
  `reptype` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  PRIMARY KEY (`repid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `teamid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`teamid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `unitno` varchar(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `analog1` varchar(5) NOT NULL,
  `analog2` varchar(5) NOT NULL,
  `analog3` varchar(5) NOT NULL,
  `analog4` varchar(5) NOT NULL,
  `digitalio` varchar(2) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `command` varchar(50) NOT NULL,
  `setcom` tinyint(1) NOT NULL,
  `commandkey` varchar(2) NOT NULL,
  `commandkeyval` varchar(20) NOT NULL,
  `acsensor` tinyint(1) NOT NULL,
  `ac_status` tinyint(1) NOT NULL,
  `analog1_sen` tinyint(1) NOT NULL,
  `analog2_sen` tinyint(1) NOT NULL,
  `analog3_sen` tinyint(1) NOT NULL,
  `analog4_sen` tinyint(1) NOT NULL,
  `digitalio_sen` tinyint(1) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `lastvisit` datetime NOT NULL,
  `visited` int(11) NOT NULL,
  `userkey` varchar(150) NOT NULL,
  `mess_email` tinyint(1) NOT NULL,
  `mess_sms` tinyint(1) NOT NULL,
  `speed_email` tinyint(1) NOT NULL,
  `speed_sms` tinyint(1) NOT NULL,
  `power_email` tinyint(1) NOT NULL,
  `power_sms` tinyint(1) NOT NULL,
  `tamper_email` tinyint(1) NOT NULL,
  `tamper_sms` tinyint(1) NOT NULL,
  `chk_email` tinyint(1) NOT NULL,
  `chk_sms` tinyint(1) NOT NULL,
  `ac_email` tinyint(1) NOT NULL,
  `ac_sms` tinyint(1) NOT NULL,
  `ignition_email` tinyint(4) NOT NULL,
  `ignition_sms` tinyint(4) NOT NULL,
  `aci_email` tinyint(1) NOT NULL,
  `aci_sms` tinyint(1) NOT NULL,
  `temp_email` tinyint(1) NOT NULL,
  `temp_sms` tinyint(1) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE IF NOT EXISTS `vehicle` (
  `vehicleid` int(11) NOT NULL AUTO_INCREMENT,
  `vehicleno` varchar(20) NOT NULL,
  `devicekey` varchar(200) NOT NULL,
  `extbatt` varchar(5) NOT NULL,
  `odometer` varchar(8) NOT NULL,
  `lastupdated` datetime NOT NULL,
  `curspeed` varchar(5) NOT NULL,
  `driverid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `description` varchar(50) NOT NULL,
  `type` varchar(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`vehicleid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
