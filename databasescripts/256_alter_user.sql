-- Insert SQL here.

ALTER TABLE `user` ADD `stateid` INT(11) NOT NULL AFTER `customerno`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 256, NOW(), 'Ganesh','alter user for stateid');
