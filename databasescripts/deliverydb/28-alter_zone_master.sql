
ALTER TABLE `zone_master` ADD `fence_id` INT(11) NOT NULL AFTER `zone_id`;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 28, NOW(), 'Ganesh Papde','delivery order column add');




