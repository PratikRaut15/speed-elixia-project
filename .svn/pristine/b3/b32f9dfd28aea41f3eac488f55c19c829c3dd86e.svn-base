-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 12, 2013 at 03:48 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `epolice`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `alertid` int(11) NOT NULL AUTO_INCREMENT,
  `alertname` varchar(255) NOT NULL,
  `alerttype` varchar(255) NOT NULL,
  `alertsubject` varchar(255) NOT NULL,
  `alertmsg` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`alertid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkpoint`
--

CREATE TABLE IF NOT EXISTS `checkpoint` (
  `checkpointid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `recipientid` int(11) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `attraction` varchar(150) NOT NULL,
  `cadd1` varchar(100) NOT NULL,
  `cadd2` varchar(100) NOT NULL,
  `cadd3` varchar(100) NOT NULL,
  `ccity` varchar(50) NOT NULL,
  `cstate` varchar(50) NOT NULL,
  `czip` varchar(50) NOT NULL,
  `cgeolat` float NOT NULL,
  `cgeolong` float NOT NULL,
  `ciconimage` varchar(100) NOT NULL,
  PRIMARY KEY (`checkpointid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `checkpointmanage`
--

CREATE TABLE IF NOT EXISTS `checkpointmanage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `checkpointid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `clientid` int(11) NOT NULL AUTO_INCREMENT,
  `clientname` varchar(50) NOT NULL,
  `add1` varchar(50) NOT NULL,
  `add2` varchar(50) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `maincontact` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `extra` varchar(50) NOT NULL,
  `iscall` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`clientid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

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
  `logoimage` varchar(50) NOT NULL,
  `bannerimage` varchar(50) NOT NULL,
  `teamid` int(11) NOT NULL,
  `notes` varchar(100) NOT NULL,
  `agreedby` varchar(100) NOT NULL,
  `agreeddate` datetime NOT NULL,
  `itemdelivery` tinyint(1) NOT NULL,
  `trackbytrackee` tinyint(1) NOT NULL,
  `finditems` tinyint(1) NOT NULL,
  `fencing` tinyint(1) NOT NULL,
  `elixiacode` tinyint(1) NOT NULL,
  `messaging` tinyint(1) NOT NULL,
  `freqdata` int(11) NOT NULL DEFAULT '0',
  `referral` varchar(100) NOT NULL,
  `service` tinyint(1) NOT NULL,
  PRIMARY KEY (`customerno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `customfield`
--

CREATE TABLE IF NOT EXISTS `customfield` (
  `customfieldid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `defaultname` varchar(100) NOT NULL,
  `usecustom` tinyint(1) NOT NULL,
  `customname` varchar(150) NOT NULL,
  PRIMARY KEY (`customfieldid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Internal Database Patch System';

-- --------------------------------------------------------

--
-- Table structure for table `dealer`
--

CREATE TABLE IF NOT EXISTS `dealer` (
  `dealerid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `company` varchar(80) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `add1` varchar(100) NOT NULL,
  `add2` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip` varchar(15) NOT NULL,
  `teamid` int(11) NOT NULL,
  `dateadded` date NOT NULL,
  PRIMARY KEY (`dealerid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `deviceid` bigint(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `devicename` varchar(150) NOT NULL,
  `devicekey` varchar(200) NOT NULL,
  `isregistered` tinyint(1) NOT NULL DEFAULT '0',
  `androidid` varchar(250) NOT NULL,
  `trackeeid` int(11) NOT NULL DEFAULT '0',
  `phoneno` varchar(50) NOT NULL,
  `devicelat` float NOT NULL,
  `devicelong` float NOT NULL,
  `lastupdated` datetime NOT NULL,
  `registeredon` datetime NOT NULL,
  `registrationapprovedon` datetime NOT NULL,
  `contract` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `pendingamount` float NOT NULL,
  `version` varchar(5) NOT NULL,
  `curversion` varchar(5) NOT NULL,
  PRIMARY KEY (`deviceid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `devinvoice`
--

CREATE TABLE IF NOT EXISTS `devinvoice` (
  `devinvoiceid` int(11) NOT NULL AUTO_INCREMENT,
  `devrate` varchar(20) NOT NULL,
  `devamount` varchar(20) NOT NULL,
  `vat` varchar(20) NOT NULL,
  `totalamount` varchar(20) NOT NULL,
  `inwords` varchar(200) NOT NULL,
  `teamid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `dateadded` date NOT NULL,
  PRIMARY KEY (`devinvoiceid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `devinvoicedetails`
--

CREATE TABLE IF NOT EXISTS `devinvoicedetails` (
  `devinvoicedetailsid` int(11) NOT NULL AUTO_INCREMENT,
  `devinvoiceid` int(11) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`devinvoicedetailsid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ecodeman`
--

CREATE TABLE IF NOT EXISTS `ecodeman` (
  `ecodemanid` int(11) NOT NULL AUTO_INCREMENT,
  `ecodeid` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
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
  `expirydate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedbackoption`
--

CREATE TABLE IF NOT EXISTS `feedbackoption` (
  `feedbackoptionid` int(11) NOT NULL AUTO_INCREMENT,
  `fquestionid` int(11) NOT NULL,
  `optionname` text NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `isdel` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`feedbackoptionid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedbackquestions`
--

CREATE TABLE IF NOT EXISTS `feedbackquestions` (
  `feedbackquestionid` int(11) NOT NULL AUTO_INCREMENT,
  `feedbackquestion` text NOT NULL,
  `customerno` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`feedbackquestionid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_result`
--

CREATE TABLE IF NOT EXISTS `feedback_result` (
  `feedbackresultid` int(11) NOT NULL AUTO_INCREMENT,
  `serviceid` int(11) NOT NULL,
  `feedbackoptionid` int(11) NOT NULL,
  `feedbackqid` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`feedbackresultid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fence`
--

CREATE TABLE IF NOT EXISTS `fence` (
  `fenceid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `fencename` varchar(100) NOT NULL,
  `conflictstatus` tinyint(1) NOT NULL,
  PRIMARY KEY (`fenceid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `ffparent`
--

CREATE TABLE IF NOT EXISTS `ffparent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `ffparentname` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `formatfield`
--

CREATE TABLE IF NOT EXISTS `formatfield` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `useformatted` tinyint(1) NOT NULL,
  `formattedkey` varchar(150) NOT NULL,
  `formattedvalue` varchar(150) NOT NULL,
  `name` varchar(100) NOT NULL,
  `reporteddate` datetime NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `ffparentid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Table structure for table `formatfieldhistory`
--

CREATE TABLE IF NOT EXISTS `formatfieldhistory` (
  `ffhid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `ff1value` varchar(150) NOT NULL,
  `ff2value` varchar(150) NOT NULL,
  `ff3value` varchar(150) NOT NULL,
  `ff4value` varchar(150) NOT NULL,
  `reporteddate` datetime NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `ffparentid` int(11) NOT NULL,
  PRIMARY KEY (`ffhid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Table structure for table `helpdesk`
--

CREATE TABLE IF NOT EXISTS `helpdesk` (
  `helpdeskid` int(11) NOT NULL AUTO_INCREMENT,
  `dateadded` date NOT NULL,
  `type` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `contactvia` varchar(50) NOT NULL,
  `reason` varchar(200) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  `resolvedby` varchar(50) NOT NULL,
  `rescomments` varchar(200) NOT NULL,
  `allottedto` varchar(50) NOT NULL,
  `bug` tinyint(1) NOT NULL,
  `bugid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  PRIMARY KEY (`helpdeskid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `itemno` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `itemdesc` varchar(200) NOT NULL,
  `trackingno` varchar(150) NOT NULL,
  `userid` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `recipientid` int(11) NOT NULL,
  `recipientname` varchar(100) NOT NULL,
  `sigreqd` tinyint(1) NOT NULL,
  `signature` varchar(200) NOT NULL,
  `signedby` varchar(100) NOT NULL,
  `deliverydate` datetime NOT NULL,
  `isdelivered` tinyint(1) NOT NULL,
  `devicekey` varchar(50) NOT NULL,
  PRIMARY KEY (`itemno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `licinvoice`
--

CREATE TABLE IF NOT EXISTS `licinvoice` (
  `licinvoiceid` int(11) NOT NULL AUTO_INCREMENT,
  `teamid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `dateadded` date NOT NULL,
  `startdate` date NOT NULL,
  `softamount` varchar(20) NOT NULL,
  `servicetax` varchar(20) NOT NULL,
  `totalamount` varchar(20) NOT NULL,
  `inwords` varchar(100) NOT NULL,
  `discount` varchar(20) NOT NULL,
  `pendingamount` varchar(20) NOT NULL,
  `subtotal` varchar(20) NOT NULL,
  PRIMARY KEY (`licinvoiceid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `licinvoicedet`
--

CREATE TABLE IF NOT EXISTS `licinvoicedet` (
  `licinvoicedetid` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  `licinvoiceid` int(11) NOT NULL,
  PRIMARY KEY (`licinvoicedetid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `msgid` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(500) NOT NULL,
  `page` varchar(50) NOT NULL,
  PRIMARY KEY (`msgid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messagemanage`
--

CREATE TABLE IF NOT EXISTS `messagemanage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `messageid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `rectype` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(150) NOT NULL,
  `message` varchar(500) NOT NULL,
  `datecreated` datetime NOT NULL,
  `senderid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `notifid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `isnotified` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isshown` tinyint(1) NOT NULL,
  PRIMARY KEY (`notifid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `prospectives`
--

CREATE TABLE IF NOT EXISTS `prospectives` (
  `prospectid` int(11) NOT NULL AUTO_INCREMENT,
  `dateadded` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `company` varchar(80) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `target` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `nextstep` varchar(100) NOT NULL,
  `sold` tinyint(1) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `referral` varchar(100) NOT NULL,
  PRIMARY KEY (`prospectid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
  `purchaseid` int(11) NOT NULL AUTO_INCREMENT,
  `dealerid` int(11) NOT NULL,
  `sold` tinyint(1) NOT NULL,
  `imei` varchar(50) NOT NULL,
  `dateadded` date NOT NULL,
  `teamid` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `cost` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `details` varchar(200) NOT NULL,
  `approval` tinyint(1) NOT NULL,
  PRIMARY KEY (`purchaseid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE IF NOT EXISTS `receipt` (
  `receiptid` int(11) NOT NULL AUTO_INCREMENT,
  `receiptno` int(11) NOT NULL,
  `receiptdate` date NOT NULL,
  `dateadded` date NOT NULL,
  `teamid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `amount` float NOT NULL,
  `approval` tinyint(1) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`receiptid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `receivereports`
--

CREATE TABLE IF NOT EXISTS `receivereports` (
  `recreportsid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `ffparentid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`recreportsid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE IF NOT EXISTS `remarks` (
  `remarkid` int(11) NOT NULL AUTO_INCREMENT,
  `remarkname` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `iscall` tinyint(1) NOT NULL,
  `timestamp` tinyint(1) NOT NULL,
  PRIMARY KEY (`remarkid`),
  KEY `remarkid` (`remarkid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `servicecall`
--

CREATE TABLE IF NOT EXISTS `servicecall` (
  `serviceid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `trackingno` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `isemail` tinyint(1) NOT NULL,
  `issms` tinyint(1) NOT NULL,
  `sigreqd` tinyint(1) NOT NULL,
  `signature` varchar(30) NOT NULL,
  `closedon` datetime NOT NULL,
  `contactperson` varchar(50) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `add1` varchar(50) NOT NULL,
  `add2` varchar(50) NOT NULL,
  `devicekey` varchar(50) NOT NULL,
  `remarkid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `uf1` varchar(150) NOT NULL,
  `uf2` varchar(150) NOT NULL,
  `callextra1` varchar(50) NOT NULL,
  `callextra2` varchar(50) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `totalbill` float NOT NULL,
  `timeslot_start` datetime NOT NULL,
  `timeslot_end` datetime NOT NULL,
  `departtime` datetime NOT NULL,
  `clientname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`serviceid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `serviceflow`
--

CREATE TABLE IF NOT EXISTS `serviceflow` (
  `serviceflowid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `servicelist`
--

CREATE TABLE IF NOT EXISTS `servicelist` (
  `servicelistid` int(11) NOT NULL AUTO_INCREMENT,
  `servicename` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `expectedtime` varchar(255) NOT NULL,
  `customerno` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`servicelistid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `servicemanage`
--

CREATE TABLE IF NOT EXISTS `servicemanage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servicelistid` int(11) NOT NULL,
  `servicecallid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `customerno` int(11) NOT NULL,
  `trackeeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `iseditedby` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `trackee`
--

CREATE TABLE IF NOT EXISTS `trackee` (
  `trackeeid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `tname` varchar(100) NOT NULL,
  `ticonimage` varchar(150) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `pushitems` tinyint(1) NOT NULL,
  `pushmessages` tinyint(1) NOT NULL,
  `pushservice` tinyint(1) NOT NULL,
  `pushcustom` tinyint(1) NOT NULL,
  `pushremarks` tinyint(1) NOT NULL,
  `pushfeedback` tinyint(1) NOT NULL,
  `pushservicelist` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`trackeeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

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
  `call_email` tinyint(1) NOT NULL,
  `call_sms` tinyint(1) NOT NULL,
  `mess_email` tinyint(1) NOT NULL,
  `mess_sms` tinyint(1) NOT NULL,
  `form_email` tinyint(1) NOT NULL,
  `form_sms` tinyint(1) NOT NULL,
  `item_email` tinyint(1) NOT NULL,
  `item_sms` tinyint(1) NOT NULL,
  `switchoff_email` tinyint(1) NOT NULL,
  `switchoff_sms` tinyint(1) NOT NULL,
  `helper` tinyint(1) NOT NULL,
  `tracking_act` tinyint(1) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `userfield1`
--

CREATE TABLE IF NOT EXISTS `userfield1` (
  `ufid1` int(11) NOT NULL AUTO_INCREMENT,
  `fieldname1` varchar(100) NOT NULL,
  `customerno` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `iscall` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`ufid1`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `userfield2`
--

CREATE TABLE IF NOT EXISTS `userfield2` (
  `ufid2` int(11) NOT NULL AUTO_INCREMENT,
  `fieldname2` varchar(100) NOT NULL,
  `customerno` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `iscall` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`ufid2`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
