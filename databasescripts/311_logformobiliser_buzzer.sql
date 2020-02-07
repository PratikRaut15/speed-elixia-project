ALTER TABLE `freeze` ADD `is_android` TINYINT(1) NOT NULL DEFAULT '0' AFTER `isdeleted`;



CREATE TABLE IF NOT EXISTS `buzzerlog`(
  `buzid` int(11) NOT NULL primary key auto_increment,
  `uid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `devicelat` float,
  `devicelong`	float, 
  `customerno`  int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon`  datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_android` tinyint(1) NOT NULL DEFAULT '0'
);


CREATE TABLE IF NOT EXISTS `immobiliserlog`(
  `mobid` int(11) NOT NULL primary key auto_increment,
  `uid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `devicelat` float,
  `devicelong`	float, 
  `commandname`  varchar(50),
  `mobiliser_flag` tinyint(1) NOT NULL DEFAULT '0', 
  `customerno`  int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon`  datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_android` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (311, NOW(), 'ganesh','create mobiliserlog or buzzer log tables /alter freeze table');

