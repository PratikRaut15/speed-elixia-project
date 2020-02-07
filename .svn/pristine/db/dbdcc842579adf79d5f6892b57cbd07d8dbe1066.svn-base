
-- Insert SQL here.
ALTER TABLE `vehicle`
ADD `average` SMALLINT( 2 ) NOT NULL AFTER `overspeed_limit` ;

ALTER TABLE `vehicle` 
CHANGE `average` `average` SMALLINT( 2 ) NOT NULL DEFAULT '0';

CREATE TABLE `fuelstorrage`(
`fuelid` INT(11) NOT NULL AUTO_INCREMENT, 
`fuel` INT(5) NOT NULL, 
`vehicleid` INT(11) NOT NULL, 
`customerno` INT(11) NOT NULL, 
`userid` INT(11) NOT NULL, 
`timestamp` DATETIME NOT NULL, 
PRIMARY KEY (`fuelid`)
) ENGINE = MyISAM;

/* New Updates */
ALTER TABLE `fuelstorrage` ADD `addedon` DATETIME NOT NULL AFTER `userid` ;

ALTER TABLE `vehicle`
ADD `fuelcapacity` TINYINT( 4 ) NOT NULL AFTER `sms_lock`; 

ALTER TABLE 
`vehicle` CHANGE `fuelcapacity` `fuelcapacity` INT( 4 ) NOT NULL;
 
ALTER TABLE `user` 
ADD `fuel_alert_sms` TINYINT( 1 ) NOT NULL AFTER `stop_alert` ,
ADD `fuel_alert_email` TINYINT( 1 ) NOT NULL AFTER `fuel_alert_sms` ,
ADD `fuel_alert_percentage` TINYINT( 2 ) NOT NULL AFTER `fuel_alert_email`;

ALTER TABLE `vehicle`
ADD `fuel_balance` INT( 4 ) NOT NULL AFTER `sms_lock`; 

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 115, NOW(), 'Shrikanth Suryawanshi', 'Average Addition');
