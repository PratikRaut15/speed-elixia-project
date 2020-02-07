-- Insert SQL here.

ALTER TABLE `vehicle` ADD `nodata_alert` INT( 11 ) NOT NULL ;

ALTER TABLE `vehicle` CHANGE `nodata_alert` `nodata_alert` BOOL NOT NULL ;

ALTER TABLE `vehicle` CHANGE `nodata_alert` `nodata_alert` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 98, NOW(), 'Sanket Sheth','No Data Alert');
