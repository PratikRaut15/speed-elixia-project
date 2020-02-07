-- Insert SQL here.

ALTER TABLE `unit` ADD `fuelsensor` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_door_opp`;
ALTER TABLE `new_sales` ADD `fuelsensor` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_door_opp`;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 230, NOW(), 'Ganesh Papde','add fuelsensor');
