ALTER TABLE `master_orders` ADD `delivery_time` DATETIME NOT NULL AFTER `delivery_long`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 27, NOW(), 'Ganesh Papde','delivery order column add');
