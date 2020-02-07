

-- Insert SQL here.

ALTER TABLE `routeman` ADD `timetaken` VARCHAR( 15 ) NOT NULL AFTER `checkpointid` ,
ADD `distance` VARCHAR( 15 ) NOT NULL AFTER `timetaken` ;

ALTER TABLE `routeman` CHANGE `distance` `distance` FLOAT( 15 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 132, NOW(), 'Shrikanth Suryawanshi','UPL_Create Route');