-- Insert SQL here.

ALTER TABLE `vehicle` DROP `description`;

ALTER TABLE  `vehicle` CHANGE  `type`  `kind` VARCHAR( 11 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 65, NOW(), 'Ajay Tripathi','Remove Unwanted Field');
