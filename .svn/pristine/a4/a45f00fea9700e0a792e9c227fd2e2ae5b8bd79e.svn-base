-- Insert SQL here.

ALTER TABLE `user` DROP `fuel_alert_percentage`;

ALTER TABLE `user` ADD `fuel_alert_percentage` INT( 3 ) NOT NULL DEFAULT '20' AFTER `fuel_alert_email` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 118, NOW(), 'Shrikanth Suryawanshi','Fuel Threshhold Limit');
