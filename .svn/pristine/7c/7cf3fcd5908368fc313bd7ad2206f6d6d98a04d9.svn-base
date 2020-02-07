-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 07, 2015 at 07:25 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sales`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_activities`( 
IN activityId1 INT, 
IN isdeleted tinyint(1),
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN

UPDATE activities 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		activityId = activityId1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_client`( 
IN clientid1 INT,
IN isdeleted tinyint(1),
IN  updatedtime DATETIME,
IN updated_by INT)
BEGIN
UPDATE clients 
	SET 
		isdeleted = isdeleted,
	        updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		clientid = clientid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_orders`( 
IN orderid1 INT,
IN isdeleted tinyint(1),
IN  updatedtime DATETIME,
IN updated_by INT
)
BEGIN
UPDATE orders 
	SET 
		isdeleted = isdeleted,
	        updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		orderid = orderid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_product`( 
IN productid1 INT,
IN isdeleted tinyint(1),
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE products 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		productid = productid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_productsinorder`( 
IN orderid1 INT,
IN isdeleted tinyint(1),
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE productsinorder 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		orderid = orderid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_reminder`( 
IN reminderid1 INT,
IN isdeleted tinyint(1),
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE reminders 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		reminderid = reminderid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_salesmanager`( 
IN srid1 INT, 
IN isdeleted tinyint(1),
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

UPDATE salesmanager 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		srid = srid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_stages`( 
IN stageid1 INT,
IN isdeleted tinyint(1),
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE stages 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		stageid = stageid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_templates`( 
IN templateid1 INT, 
IN isdeleted tinyint(1),
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN

UPDATE templates 
	SET 
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		templateid = templateid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_activities`( 
IN orderid INT, 
IN reminderid INT,
IN notes TEXT, 
IN activitytime datetime,
IN activity_reminder_duration INT,
IN isactivitydone tinyint(1),
IN isemailrequested tinyint(1),
IN issmsrequested tinyint(1),
IN isemailsent tinyint(1),
IN issmssent tinyint(1),
IN paymentamount float(10,2),
IN activitytype varchar(50),
IN userid INT,
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO activities (orderid,reminderid,notes,activitytime,activity_reminder_duration,isactivitydone,isemailrequested,isemailsent,issmssent,paymentamount,activitytype,userid,customerno, entrytime, addedby, isdeleted) VALUES (orderid,reminderid,notes,activitytime,activity_reminder_duration,isactivitydone,isemailrequested,isemailsent,issmssent,paymentamount,activitytype,userid,customerno, entrytime, addedby,0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_client`( 
IN name VARCHAR (100),
IN address TEXT, 
IN mobileno INT,
IN email VARCHAR(100), 
IN dob date, 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO clients (name, address, email, mobileno, dob, customerno, entrytime, addedby, isdeleted) VALUES ( name, address, email, mobileno, dob, customerno,
entrytime, addedby, 0 );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_orders`( 
OUT lastid INT,
IN clientid INT,
IN stageid INT,
IN expectedordercomplitiondate date,
IN isemailrequested tinyint(1),
IN issmsrequested tinyint(1),
IN isemailsent tinyint(1),
IN issmssent tinyint(1),
IN additionalcost float(6,2),
IN totalamount float(6,2),
IN customerno INT,
IN  entrytime DATETIME,
IN addedby INT
)
BEGIN

INSERT INTO orders (clientid, stageid, expectedordercomplitiondate, isemailrequested,issmsrequested, isemailsent, issmssent,additionalcost,totalamount, customerno, entrytime, addedby, isdeleted) VALUES (clientid, stageid, expectedordercomplitiondate, isemailrequested, issmsrequested, isemailsent, issmssent,additionalcost,totalamount, customerno, entrytime, addedby,0);

SET lastid = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_product`( 
IN productname VARCHAR (100), 
IN unit_price float(6,2), 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO products (productname, unit_price,customerno, entrytime, addedby, isdeleted) VALUES ( productname, unit_price,customerno, entrytime, addedby, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_productsinorder`( 
IN orderid INT, 
IN productid INT, 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO productsinorder (orderid,productid, customerno, entrytime, addedby, isdeleted) VALUES ( orderid,productid,customerno, entrytime, addedby, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_reminder`( 
IN remindername VARCHAR (100), 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO reminders (remindername, customerno, entrytime, addedby, isdeleted) VALUES ( remindername,customerno, entrytime, addedby, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_reminder_test`( 
IN remindername VARCHAR (100), 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT,
OUT out_param INT 
)
BEGIN

INSERT INTO reminders (remindername, customerno, entrytime, addedby, isdeleted) VALUES ( remindername,customerno, entrytime, addedby, 0);
SET out_param = LAST_INSERT_ID();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_salesmanager`( 
IN srname VARCHAR (50), 
IN sremail VARCHAR (50), 
IN srphone VARCHAR (15),
IN userkey varchar(150), 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO salesmanager (srname,sremail,srphone,userkey, customerno, entrytime, addedby, isdeleted) VALUES ( srname,sremail,srphone,userkey,customerno, entrytime, addedby, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_stages`( 
IN stagename VARCHAR (100), 
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO stages (stagename, customerno, entrytime, addedby, isdeleted) VALUES ( stagename,customerno, entrytime, addedby, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_templates`( 
IN reminderid INT, 
IN Stageid INT,
IN template_type varchar(50), 
IN email_subject varchar(300),
IN emailtemplate text,
IN smstemplate varchar(100),
IN recipienttype varchar(25),
IN customerno INT, 
IN  entrytime DATETIME, 
IN addedby INT
)
BEGIN

INSERT INTO templates (reminderid,Stageid,template_type,email_subject,emailtemplate,smstemplate,recipienttype,customerno, entrytime, addedby, isdeleted) VALUES ( reminderid,Stageid,template_type,email_subject,emailtemplate,smstemplate,recipienttype,customerno, entrytime, addedby, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_activities`( 
IN activityId1 INT, 
IN orderid1 INT, 
IN reminderid INT,
IN notes TEXT, 
IN activitytime datetime,
IN activity_reminder_duration INT,
IN isactivitydone tinyint(1),
IN isemailrequested tinyint(1),
IN issmsrequested tinyint(1),
IN isemailsent tinyint(1),
IN issmssent tinyint(1),
IN paymentamount float(10,2),
IN activitytype varchar(50),
IN userid INT,
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN

UPDATE activities 
	SET 
		orderid = orderid,
		reminderid = reminderid,
		notes = notes,
		activitytime = activitytime,
		activity_reminder_duration = activity_reminder_duration,
		isactivitydone = isactivitydone,
		isemailrequested = isemailrequested,
		issmsrequested = issmsrequested,
		isemailsent = isemailsent,
		issmssent = issmssent,
		paymentamount = paymentamount,
		activitytype = activitytype,
		userid = userid,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		activityId = activityId1 AND orderid = orderid1 ;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_client`( 
IN clientid1 INT,
IN name VARCHAR (100),
IN address TEXT, 
IN mobileno varchar(15),
IN email VARCHAR(100), 
IN dob date, 
IN  updatedtime DATETIME, 
IN updated_by INT)
BEGIN
UPDATE clients 
	SET 
		name = name,
		address = address, 
		email = email,
		mobileno = mobileno, 
		dob = dob,
	        updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		clientid = clientid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_orders`( 
IN orderid1 INT,
IN clientid INT,
IN stageid INT,
IN expectedordercomplitiondate date,
IN isemailrequested tinyint(1),
IN issmsrequested tinyint(1),
IN isemailsent tinyint(1),
IN issmssent tinyint(1),
IN additionalcost float(6,2),
IN totalamount float(6,2),
IN  updatedtime DATETIME,
IN updated_by INT
)
BEGIN
UPDATE orders 
	SET 
		clientid = clientid,
		stageid = stageid,
		expectedordercomplitiondate = expectedordercomplitiondate,
		isemailrequested = isemailrequested, 
		issmsrequested = issmsrequested,
		isemailsent = isemailsent,
		issmssent = issmssent,
		additionalcost = additionalcost,
		totalamount = totalamount,
	        updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		orderid = orderid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_product`( 
IN productid1 INT,
IN productname VARCHAR (100), 
IN unit_price float(6,2), 
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE products 
	SET 
		productname = productname,
		unit_price = unit_price, 
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		productid = productid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_productsinorder`( 
IN product_ord_map_id1 INT,
IN orderid INT, 
IN productid INT, 
IN customerno INT, 
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE productsinorder 
	SET 
		orderid = orderid,
		productid = productid,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		product_ord_map_id = product_ord_map_id1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_reminder`( 
IN reminderid1 INT,
IN remindername VARCHAR (100), 
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE reminders 
	SET 
		remindername = remindername,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		reminderid = reminderid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_salesmanager`( 
IN srid1 INT, 
IN srname VARCHAR (50), 
IN sremail VARCHAR (50), 
IN srphone VARCHAR (15),
IN entrytime DATETIME, 
IN addedby INT
)
BEGIN

UPDATE salesmanager 
	SET 
		srname = srname,
		sremail = sremail,
		srphone = srphone,	
		isdeleted = isdeleted,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		srid = srid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_stages`( 
IN stageid1 INT,
IN stagename VARCHAR (100), 
IN  updatedtime DATETIME, 
IN updated_by INT
)
BEGIN
UPDATE stages 
	SET 
		stagename = stagename,
		updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		stageid = stageid1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_templates`( 
IN templateid1 INT, 
IN reminderid INT, 
IN Stageid INT,
IN template_type varchar(50), 
IN email_subject varchar(300),
IN emailtemplate text,
IN smstemplate varchar(100),
IN recipienttype varchar(25),
IN updatedtime DATETIME, 
IN updated_by INT
)
BEGIN

UPDATE templates 
	SET 
		templateid = templateid1,
		reminderid = reminderid,
		StageId = Stageid,
		template_type = template_type,
		email_subject = email_subject,
		emailtemplate = emailtemplate,
		smstemplate = smstemplate,
		recipienttype = recipienttype,
		updatedtime = updatedtime, 
		updated_by = updated_by 
	WHERE 
		templateid = templateid1;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
`activityId` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `reminderid` int(11) NOT NULL,
  `notes` text,
  `activitytime` datetime DEFAULT NULL,
  `activity_reminder_duration` int(5) DEFAULT NULL,
  `isactivitydone` tinyint(1) DEFAULT '0',
  `isemailrequested` tinyint(1) DEFAULT '0',
  `issmsrequested` tinyint(1) DEFAULT '0',
  `isemailsent` tinyint(1) DEFAULT '0',
  `issmssent` tinyint(1) DEFAULT '0',
  `paymentamount` float(10,2) DEFAULT NULL,
  `activitytype` varchar(50) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
`clientid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(55) NOT NULL,
  `mobileno` varchar(15) NOT NULL,
  `dob` date DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dbpatches`
--

CREATE TABLE IF NOT EXISTS `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
`orderid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `stageid` int(11) NOT NULL,
  `expectedordercomplitiondate` date DEFAULT NULL,
  `isemailrequested` tinyint(1) DEFAULT '0',
  `issmsrequested` tinyint(1) DEFAULT '0',
  `isemailsent` tinyint(1) DEFAULT '0',
  `issmssent` tinyint(1) DEFAULT '0',
  `additionalcost` float(6,2) DEFAULT NULL,
  `totalamount` float(6,2) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
`productid` int(11) NOT NULL,
  `productname` varchar(100) NOT NULL,
  `unit_price` float(6,2) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productsinorder`
--

CREATE TABLE IF NOT EXISTS `productsinorder` (
`product_ord_map_id` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE IF NOT EXISTS `reminders` (
`reminderid` int(11) NOT NULL,
  `remindername` varchar(100) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stages`
--

CREATE TABLE IF NOT EXISTS `stages` (
`stageid` int(11) NOT NULL,
  `stagename` varchar(100) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
`templateid` int(11) NOT NULL,
  `reminderid` int(11) NOT NULL,
  `StageId` int(11) NOT NULL,
  `template_type` varchar(10) DEFAULT NULL,
  `isemailrequested` tinyint(1) DEFAULT '0',
  `issmsrequested` tinyint(1) DEFAULT '0',
  `email_subject` varchar(300) NOT NULL,
  `emailtemplate` text NOT NULL,
  `smstemplate` varchar(100) NOT NULL,
  `recipienttype` varchar(25) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime DEFAULT NULL,
  `addedby` int(11) DEFAULT NULL,
  `updatedtime` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
 ADD PRIMARY KEY (`activityId`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
 ADD PRIMARY KEY (`clientid`);

--
-- Indexes for table `dbpatches`
--
ALTER TABLE `dbpatches`
 ADD PRIMARY KEY (`patchid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
 ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
 ADD PRIMARY KEY (`productid`);

--
-- Indexes for table `productsinorder`
--
ALTER TABLE `productsinorder`
 ADD PRIMARY KEY (`product_ord_map_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
 ADD PRIMARY KEY (`reminderid`);

--
-- Indexes for table `stages`
--
ALTER TABLE `stages`
 ADD PRIMARY KEY (`stageid`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
 ADD PRIMARY KEY (`templateid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
MODIFY `activityId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
MODIFY `clientid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `productsinorder`
--
ALTER TABLE `productsinorder`
MODIFY `product_ord_map_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
MODIFY `reminderid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `stages`
--
ALTER TABLE `stages`
MODIFY `stageid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
MODIFY `templateid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
