-- Insert SQL here.

ALTER TABLE `user` ADD `rt_status_filter` INT( 11 ) NOT NULL ,
ADD `rt_stoppage_filter` INT( 11 ) NOT NULL ;
ALTER TABLE `vehicle` ADD `ign_flag` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `vehicle` CHANGE `ign_flag` `stoppage_flag` TINYINT( 1 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 101, NOW(), 'Sanket Sheth','Real Time Filters');
