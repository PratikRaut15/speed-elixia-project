-- Insert SQL here.

CREATE TABLE `temp_compliance` (`temp_compliance_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `uid` INT(11) NOT NULL, `vehicleid` INT(11) NOT NULL, `bc1` INT(11) NOT NULL, `gc_c1` INT(11) NOT NULL, `gc_nc1` INT(11) NOT NULL, `min_1` VARCHAR(5) NOT NULL, `max_1` VARCHAR(5) NOT NULL, `min_range_1` INT(11) NOT NULL, `max_range_1` INT(11) NOT NULL, `bc2` INT(11) NOT NULL, `gc_c2` INT(11) NOT NULL, `gc_nc2` INT(11) NOT NULL, `min_2` VARCHAR(5) NOT NULL, `max_2` VARCHAR(5) NOT NULL, `min_range_2` INT(11) NOT NULL, `max_range_2` INT(11) NOT NULL, `bc3` INT(11) NOT NULL, `gc_c3` INT(11) NOT NULL, `gc_nc3` INT(11) NOT NULL, `min_3` VARCHAR(5) NOT NULL, `max_3` VARCHAR(5) NOT NULL, `min_range_3` INT(11) NOT NULL, `max_range_3` INT(11) NOT NULL, `bc4` INT(11) NOT NULL, `gc_c4` INT(11) NOT NULL, `gc_nc4` INT(11) NOT NULL, `min_4` VARCHAR(5) NOT NULL, `max_4` VARCHAR(5) NOT NULL, `min_range_4` INT(11) NOT NULL, `max_range_4` INT(11) NOT NULL, `timestamp` DATETIME NOT NULL) ENGINE = InnoDB;
ALTER TABLE `temp_compliance` ADD `customerno` INT( 11 ) NOT NULL ;
ALTER TABLE `temp_compliance` ADD `range_change_timestamp` DATETIME NOT NULL ;


UPDATE `maintenance` SET roleid =18 WHERE customerno = 118 AND category IN (0,1) AND amount_quote > 2500; 
UPDATE `maintenance_history` SET roleid =18 WHERE customerno = 118 AND category IN (0,1) AND amount_quote > 2500;    
UPDATE `maintenance` SET roleid =19 WHERE customerno = 118 AND category IN (0,1) AND amount_quote <= 2500; 
UPDATE `maintenance_history` SET roleid =19 WHERE customerno = 118 AND category IN (0,1) AND amount_quote <= 2500; 

ALTER TABLE `vehicle` ADD `temp1_mute` BOOLEAN NOT NULL AFTER `temp1_max` ;
ALTER TABLE `vehicle` ADD `temp2_mute` BOOLEAN NOT NULL AFTER `temp2_max` ;
ALTER TABLE `vehicle` ADD `temp3_mute` BOOLEAN NOT NULL AFTER `temp3_max` ;
ALTER TABLE `vehicle` ADD `temp4_mute` BOOLEAN NOT NULL AFTER `temp4_max` ;

CREATE TABLE `temp_mute` (
`muteid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vehicleid` INT( 11 ) NOT NULL ,
`unitid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`temp_type` TINYINT( 4 ) NOT NULL ,
`mute_starttime` DATETIME NOT NULL ,
`mute_endtime` DATETIME NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `temp_mute` ADD `userid` INT( 11 ) NOT NULL ,
ADD `timestamp` DATETIME NOT NULL ;

CREATE TABLE `temp_non_compliance` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vehicleid` INT( 11 ) NOT NULL ,
`uid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`temp_type` TINYINT( 4 ) NOT NULL ,
`nc_st` DATETIME NOT NULL ,
`nc_et` DATETIME NOT NULL ,
`nc_reasonid` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`timestamp` INT( 11 ) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `unit` ADD `door_digitalio` TINYINT( 4 ) NOT NULL AFTER `digitalio` ;
ALTER TABLE `unit` CHANGE `door_digitalio` `door_digitalio` VARCHAR( 2 ) NOT NULL ;

ALTER TABLE `unit` ADD `door_digitalioupdated` DATETIME NOT NULL AFTER `digitalioupdated` ;

ALTER TABLE `unit` DROP `door_digitalio` ;

ALTER TABLE `unit` ADD `extra2_digital` TINYINT( 1 ) NOT NULL AFTER `extra_digital` ;
ALTER TABLE `unit` ADD `extra2_digitalioupdated` DATETIME NOT NULL AFTER `extra_digitalioupdated` ;

ALTER TABLE `temp_mute` CHANGE `unitid` `uid` INT( 11 ) NOT NULL ;

ALTER TABLE `temp_mute` DROP `userid` ;
ALTER TABLE `temp_non_compliance` DROP `userid` ;

ALTER TABLE `unit` DROP `extra2_digital` ;

ALTER TABLE `temp_non_compliance` CHANGE `timestamp` `timestamp` DATETIME NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 360, NOW(), 'Sanket Sheth','TCPListener Changes');
