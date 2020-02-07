
ALTER TABLE `customer` ADD `use_freeze` TINYINT(1) NOT NULL DEFAULT '0' AFTER `use_immobiliser`;

ALTER TABLE `unit` ADD `is_freeze` TINYINT(1) NOT NULL DEFAULT '0' AFTER `mobiliser_flag`;

CREATE TABLE IF NOT EXISTS `freeze`(
  `fid` int(11) NOT NULL primary key auto_increment,
  `uid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `devicelat` float,
  `devicelong`	float, 
  `customerno`  int(11),
  `createdby` int(11),
  `createdon` datetime NOT NULL,
  `updatedby` int(11),
  `updatedon`  datetime not null,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (310, NOW(), 'ganesh','altertables -(customer,unit) // addtable-(freeze)');

