-- Insert SQL here.

ALTER TABLE `dailyreport` ADD `last_online_updated` DATETIME NOT NULL ;
ALTER TABLE `dailyreport` ADD `offline_data_time` INT( 11 ) NOT NULL ;

CREATE TABLE `nodata_chunk` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`customerno` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`starttime` DATETIME NOT NULL ,
`endtime` DATETIME NOT NULL ,
`duration` INT( 11 ) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `towing_details` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`customerno` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`towing_time` DATETIME NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `towing_details` ADD `speed` INT( 11 ) NOT NULL ;

ALTER TABLE `vehicle` CHANGE `odometer` `odometer` VARCHAR( 15 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 198, NOW(), 'Sanket Sheth','Last Online Updated');
