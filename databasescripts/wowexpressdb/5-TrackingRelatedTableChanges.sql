USE wowexpress;

ALTER TABLE `orderrequest` ADD `isdeleted` TINYINT NOT NULL ;
ALTER TABLE `orderrequest` ADD `trackingstatusid` TINYINT NOT NULL AFTER `paymentmodeid`;

CREATE TABLE `orderrequesthistory` (
  `orderhistoryid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `fromaddressid` varchar(50) NOT NULL,
  `toaddressid` varchar(50) NOT NULL,
  `pickupdate` datetime NOT NULL,
  `slotid` int(11) NOT NULL,
  `paymentmodeid` int(11) NOT NULL,
  `trackingstatusid` tinyint(4) NOT NULL,
  `addedon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`orderhistoryid`)
);

CREATE TABLE `orderreturn` (
  `orderreturnid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `fromaddressid` varchar(50) NOT NULL,
  `toaddressid` varchar(50) NOT NULL,
  `pickupdate` datetime NOT NULL,
  `slotid` int(11) NOT NULL,
  `paymentmodeid` int(11) NOT NULL,
  `trackingstatusid` tinyint(4) NOT NULL,
  `addedon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`orderhistoryid`)
);

CREATE TABLE `orderreturnhistory` (
  `orderreturnhistoryid` int(11) NOT NULL AUTO_INCREMENT,
  `orderreturnid` int(11),
  `orderid` int(11) NOT NULL,
  `fromaddressid` varchar(50) NOT NULL,
  `toaddressid` varchar(50) NOT NULL,
  `pickupdate` datetime NOT NULL,
  `slotid` int(11) NOT NULL,
  `paymentmodeid` int(11) NOT NULL,
  `trackingstatusid` tinyint(4) NOT NULL,
  `addedon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`orderhistoryid`)
);

DROP TABLE IF EXISTS `trackingstatus`;
CREATE TABLE `trackingstatus` (
  `trackingstatusid` tinyint(4) NOT NULL,
  `trackingstatusname` varchar(50) NOT NULL,
  `tracking_sequence` tinyint(4) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

INSERT INTO `trackingstatus` (`trackingstatusid`, `trackingstatusname`, `tracking_sequence`, `isdeleted`) VALUES
(1, 'Pickedup', 1, 0),
(2, 'Arrived at WOW hub', 2, 0),
(3, 'Out for delivery', 3, 0),
(4, 'Delivery status', 4, 0);

ALTER TABLE `trackingstatus`
  ADD PRIMARY KEY (`trackingstatusid`);

ALTER TABLE `trackingstatus`
  MODIFY `trackingstatusid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
  
  INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (5, NOW(), 'Mrudang','Changes for return and tracking');
