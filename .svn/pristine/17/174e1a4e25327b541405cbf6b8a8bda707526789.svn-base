-- Insert SQL here.
ALTER TABLE `maintenance` CHANGE `tyre_type` `tyre_type` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `maintenance_history` CHANGE `tyre_type` `tyre_type` VARCHAR( 255 ) NOT NULL ;



ALTER TABLE `maintenance` ADD `parts_list` VARCHAR( 255 ) NOT NULL AFTER `tyre_type` ,
ADD `task_select_array` VARCHAR( 255 ) NOT NULL AFTER `parts_list` ;

ALTER TABLE `maintenance` ADD `approval_notes` VARCHAR( 255 ) NOT NULL AFTER `notes` ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (88, NOW(), 'vishu','alert for tyre and others');
