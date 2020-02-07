-- Insert SQL here.

alter table user add column dailyemail tinyint(1) default 0;

ALTER TABLE `driver` ADD INDEX ( `vehicleid` ) ;
ALTER TABLE `vehicle` ADD INDEX ( `uid` ) ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 137, NOW(), 'Akhil VL','New column, dailyemail in user table');
