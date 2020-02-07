
ALTER TABLE `orderrequest` ADD `pickupboyid` INT(11) NOT NULL AFTER `orderid`;


CREATE TABLE IF NOT EXISTS `awbseed`(
  `seedid` int(11) NOT NULL primary key auto_increment,
  `orderid` int(11) NOT NULL,
  `awbno` varchar(15) NOT NULL,
  `addedby` int(11) NOT NULL,
  `createdtime` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);


CREATE TABLE IF NOT EXISTS `pickupboy`(
  `pid` int(11) NOT NULL primary key auto_increment,
  `userid` int(11) NOT NULL,
  `pickupboyname` varchar(50) NOT NULL,
  `emailid` varchar(50) NOT NULL,
  `phoneno` varchar(15) NOT NULL,
  `pincode` varchar(15) NOT NULL,
`pickupboyphoto` text NOT NULL,
  `addedby` int(11) NOT NULL,
  `createdtime` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (9, NOW(), 'Ganesh Papde','Order request add pickupboyid and pickupboy table');
