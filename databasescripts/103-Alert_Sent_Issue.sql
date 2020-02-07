-- Insert SQL here.

ALTER TABLE `vehicle` DROP `alert_sent` ;
ALTER TABLE `stoppage_alerts` ADD `alert_sent` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `stoppage_alerts` ADD `vehicleid` INT( 11 ) NOT NULL ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 103, NOW(), 'Sanket Sheth','Alert_Sent_Issue');
