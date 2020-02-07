-- Insert SQL here.

ALTER TABLE `cash_received` ADD `advp_status` TINYINT(1) NOT NULL DEFAULT '0' AFTER `isdeleted`;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 193, NOW(), 'Ganesh Papde','Add column advp_status');
