-- Insert SQL here.

ALTER TABLE `vehicle` ADD `notes` VARCHAR(255) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 249, NOW(), 'Ganesh','Add notes column');
