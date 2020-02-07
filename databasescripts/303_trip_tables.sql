
CREATE TABLE IF NOT EXISTS `tripdetails` (
`tripid` int(11) NOT NULL primary key auto_increment,
  `vehicleno` varchar(20) NOT NULL,
  `triplogno` varchar(20) NOT NULL,
  `tripstatusid` int(5) NOT NULL,	
  `routename` varchar(20) NOT NULL,
  `budgetedkms` integer(11) NOT NULL,
  `budgetedhrs` integer(11) NOT NULL,
  `consignor` varchar(50) NOT NULL,
  `consignee` varchar(50) NOT NULL,
  `consignorid` int(11) NOT NULL,
  `consigneeid` int(11) NOT NULL,
  `billingparty` varchar(50) NOT NULL,
  `mintemp` decimal(11,2) NOT NULL,
  `maxtemp` decimal(11,2) NOT NULL,
  `drivername` varchar(50) NOT NULL,
  `drivermobile1` varchar(15) NOT NULL,
  `drivermobile2` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);


CREATE TABLE IF NOT EXISTS `tripdetail_history` (
`triphisid` int(11) NOT NULL primary key auto_increment,
  `tripid` int(11) NOT NULL, 	
  `vehicleno` varchar(20) NOT NULL,
  `triplogno` varchar(20) NOT NULL,
  `tripstatusid` int(5) NOT NULL,	
  `routename` varchar(20) NOT NULL,
  `budgetedkms` integer(11) NOT NULL,
  `budgetedhrs` integer(11) NOT NULL,
  `consignor` varchar(50) NOT NULL,
  `consignee` varchar(50) NOT NULL,
  `billingparty` varchar(50) NOT NULL,
  `mintemp` decimal(11,2) NOT NULL,
  `maxtemp` decimal(11,2) NOT NULL,
  `drivername` varchar(50) NOT NULL,
  `drivermobile1` varchar(15) NOT NULL,
  `drivermobile2` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);





CREATE TABLE IF NOT EXISTS `tripconsignee` (
`consid` int(11) NOT NULL primary key auto_increment,
 `consigneename` varchar(50) NOT NULL,
 `email` varchar(50) NOT NULL,
 `phone` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);


CREATE TABLE IF NOT EXISTS `tripconsignor` (
`consrid` int(11) NOT NULL primary key auto_increment,
 `consignorname` varchar(50) NOT NULL,
 `email` varchar(50) NOT NULL,
 `phone` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);



CREATE TABLE IF NOT EXISTS `tripstatus` (
`tripstatusid` int(11) NOT NULL primary key auto_increment,
 `tripstatus` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (303, NOW(), 'Ganesh','trip tables');
