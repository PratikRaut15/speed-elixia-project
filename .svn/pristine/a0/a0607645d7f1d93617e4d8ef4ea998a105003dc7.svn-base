-- Insert SQL here.

ALTER TABLE `unit` ADD `repairtat` DATE NOT NULL AFTER `unitno`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 181, NOW(), 'Ganesh Papde','add coloumn repairtat(date)');
