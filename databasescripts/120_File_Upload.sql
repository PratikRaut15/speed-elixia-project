-- Insert SQL here.

ALTER TABLE `vehicle` ADD `other_upload3` VARCHAR( 250 ) NOT NULL AFTER `other_upload2` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 120, NOW(), 'Sanket Sheth','File_Upload');
