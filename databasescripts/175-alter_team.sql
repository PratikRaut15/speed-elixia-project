-- Insert SQL here.

ALTER TABLE `team` ADD `distributor_id` INT(11) DEFAULT NULL; 
ALTER TABLE `team` ADD `address` VARCHAR(255) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `distributor_purchase` (
`uid` int(11) AUTO_INCREMENT PRIMARY KEY,
  `teamid` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `device_qty` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) DEFAULT 0
); 

CREATE TABLE IF NOT EXISTS `retailer_details` (
`uid` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(255) default NULL,
  `vehiclenumber` varchar(255) default NULL,
  `installdate` date default NULL,
  `unitid` int(11) NOT NULL,
  `dealer_id` int(11) NOT NULL,
  `overspeed` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) DEFAULT 0
);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 175, NOW(), 'Ganesh Papde','alter team, new distributor_purchase,new retailer_details');
