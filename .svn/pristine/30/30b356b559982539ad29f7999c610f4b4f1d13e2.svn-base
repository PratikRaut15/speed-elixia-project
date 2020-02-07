-- Insert SQL here.

ALTER TABLE `new_sales` ADD `rel_manager` VARCHAR(5) NOT NULL AFTER `customerno`;

ALTER TABLE `relationship_manager` ADD `teamid` INT(11) NOT NULL AFTER `rid`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 222, NOW(), 'Ganesh','alter tables');
