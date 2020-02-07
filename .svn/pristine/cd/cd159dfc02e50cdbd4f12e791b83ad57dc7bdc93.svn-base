
DROP DATABASE pickupdb; CREATE database pickupdb;

use pickupdb;

CREATE TABLE IF NOT EXISTS `area_master` (
`area_master_id` int(11) NOT NULL,
  `customerno` int(11) DEFAULT '1',
  `zone_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `areaname` varchar(255) DEFAULT NULL,
  `lat` varchar(25) DEFAULT NULL,
  `lng` varchar(25) DEFAULT NULL,
  `entry_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `order_route_sequence` (
`sequence_id` int(11) NOT NULL,
  `pickupid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `sequence` int(5) NOT NULL,
  `time_taken` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pickup_customer` (
`customerid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `customername` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pickup_order` (
`oid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `customerid` varchar(20) NOT NULL,
  `vendorno` varchar(20) NOT NULL,
  `orderid` varchar(35) NOT NULL,
  `fulfillmentid` varchar(35) NOT NULL,
  `awbno` varchar(35) NOT NULL,
  `shipperid` varchar(20) NOT NULL,
  `pickupboyid` varchar(35) NOT NULL,
  `pickupdate` varchar(20) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pickup_shiper` (
`sid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `sname` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pickup_user` (
`pid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pickup_vendor` (
`vendorid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `vendorno` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `vendorname` varchar(50) NOT NULL,
  `vendorcompany` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `phone2` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `lat` varchar(25) NOT NULL,
  `lng` varchar(25) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pinmapping` (
`mpid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `pincode` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `slot_master` (
`slot_id` int(11) NOT NULL,
  `customerno` int(11) DEFAULT NULL,
  `slotname` varchar(105) DEFAULT NULL,
  `customer_slot_id` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `vendormapping` (
`mid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `vendor_no` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `zone_master` (
`zone_master_id` int(11) NOT NULL,
  `customerno` int(11) DEFAULT '1',
  `zone_id` int(11) DEFAULT NULL,
  `zonename` varchar(255) DEFAULT NULL,
  `start_lat` varchar(55) DEFAULT '19.06',
  `start_long` varchar(55) DEFAULT '72.89',
  `groupid` int(11) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `area_master`
 ADD PRIMARY KEY (`area_master_id`), ADD KEY `zone_id` (`zone_id`), ADD KEY `area_id` (`area_id`);




ALTER TABLE `order_route_sequence`
 ADD PRIMARY KEY (`sequence_id`);

ALTER TABLE `pickup_customer`
 ADD PRIMARY KEY (`customerid`);


ALTER TABLE `pickup_order`
 ADD PRIMARY KEY (`oid`);


ALTER TABLE `pickup_shiper`
 ADD PRIMARY KEY (`sid`);


ALTER TABLE `pickup_user`
 ADD PRIMARY KEY (`pid`);


ALTER TABLE `pickup_vendor`
 ADD PRIMARY KEY (`vendorid`);


ALTER TABLE `pinmapping`
 ADD PRIMARY KEY (`mpid`);


ALTER TABLE `slot_master`
 ADD PRIMARY KEY (`slot_id`);

ALTER TABLE `vendormapping`
 ADD PRIMARY KEY (`mid`);

ALTER TABLE `zone_master`
 ADD PRIMARY KEY (`zone_master_id`);


ALTER TABLE `area_master`
MODIFY `area_master_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;



ALTER TABLE `order_route_sequence`
MODIFY `sequence_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `pickup_customer`
MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `pickup_order`
MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `pickup_shiper`
MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `pickup_user`
MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pickup_vendor`
MODIFY `vendorid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `pinmapping`
MODIFY `mpid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `slot_master`
MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `vendormapping`
MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `zone_master`
MODIFY `zone_master_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;










INSERT INTO `pickup_customer` (`customerid`, `customerno`, `customername`, `phone`, `email`, `address`, `isdeleted`) VALUES
(1, 127, 'Rediffmail', '789654123', 'rediff@elixia.com', 'Neelkanth                        ', 0),
(2, 127, 'PayTM', '852963712', 'pay@paytm.com', 'Neekanth            ', 0);


CREATE TABLE IF NOT EXISTS `pickup_reason` (
`reasonid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `reason` text NOT NULL,
  `isdeleted` tinyint(4) NOT NULL,
  `timestamp` datetime NOT NULL,
  `flag` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

ALTER TABLE `pickup_reason`
 ADD PRIMARY KEY (`reasonid`);
ALTER TABLE `pickup_reason`
MODIFY `reasonid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

INSERT INTO `pickup_reason` (`reasonid`, `customerno`, `userid`, `reason`, `isdeleted`, `timestamp`, `flag`) VALUES
(1, 0, 0, 'No one at home', 0, '2014-11-03 13:08:25', 0),
(2, 0, 0, 'TEs Test Ets', 1, '2015-04-09 09:57:52', 0),
(3, 0, 0, 'Incorrect Address', 0, '2014-11-06 13:09:32', 0);


CREATE TABLE IF NOT EXISTS `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL,
  `flag` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `dbpatches`
 ADD PRIMARY KEY (`patchid`);


ALTER TABLE pickup_order ADD reasonid varchar(10) AFTER status;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 1, NOW(), 'Shrikant Suryawanshi','Latest Pickup DB ');

