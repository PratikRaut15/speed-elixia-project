-- Insert SQL here.
ALTER TABLE `maintenance_history` ADD `parts_list` VARCHAR( 255 ) NOT NULL AFTER `file_name` ,
ADD `task_select_array` VARCHAR( 255 ) NOT NULL AFTER `parts_list` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 105, NOW(), 'vishu','alter maintanace history');
