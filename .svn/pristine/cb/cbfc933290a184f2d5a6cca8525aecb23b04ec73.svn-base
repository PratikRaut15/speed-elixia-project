-- Insert SQL here.

ALTER TABLE `customer` ADD `sms_count` INT( 11 ) NOT NULL AFTER `smsleft` ,
ADD `sms_lock` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `sms_count` ;



ALTER TABLE `customer`
  DROP `sms_count`,
  DROP `sms_lock`;

ALTER TABLE `vehicle` ADD `sms_count` INT( 11 ) NOT NULL AFTER `description` ,
ADD `sms_lock` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `sms_count` ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 112, NOW(), 'Shrikanth Suryawanshi', '112-SMS Check In Cron');
