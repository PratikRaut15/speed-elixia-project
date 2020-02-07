-- Insert SQL here.

CREATE TABLE `temp_inactive` ( `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `vehicleid` INT(11) NOT NULL , `uid` INT(11) NOT NULL , `customerno` INT(11) NOT NULL , `temp_type` INT(4) NOT NULL , `start_time` DATETIME NOT NULL , `end_time` DATETIME NOT NULL , `timestamp` DATETIME NOT NULL ) ENGINE = MyISAM;

ALTER TABLE `unit` ADD `bc1_inactive` TINYINT NOT NULL AFTER `isRequiredThirdParty`, ADD `bc2_inactive` TINYINT NOT NULL AFTER `bc1_inactive`, ADD `bc3_inactive` TINYINT NOT NULL AFTER `bc2_inactive`, ADD `bc4_inactive` TINYINT NOT NULL AFTER `bc3_inactive`;

CREATE TABLE `device_inactive` ( `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `vehicleid` INT(11) NOT NULL , `uid` INT(11) NOT NULL , `customerno` INT(11) NOT NULL , `reason` INT(4) NOT NULL , `start_time` DATETIME NOT NULL , `end_time` DATETIME NOT NULL , `timestamp` DATETIME NOT NULL ) ENGINE = MyISAM;

ALTER TABLE `device_inactive` ADD `start_lat` FLOAT NOT NULL AFTER `timestamp`, ADD `start_long` FLOAT NOT NULL AFTER `start_lat`, ADD `end_lat` FLOAT NOT NULL AFTER `start_long`, ADD `end_long` FLOAT NOT NULL AFTER `end_lat`;

ALTER TABLE `temp_inactive` ADD `start_lat` FLOAT NOT NULL AFTER `timestamp`, ADD `start_long` FLOAT NOT NULL AFTER `start_lat`, ADD `end_lat` FLOAT NOT NULL AFTER `start_long`, ADD `end_long` FLOAT NOT NULL AFTER `end_lat`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 403, NOW(), 'Sanket Sheth','Inactive List');
