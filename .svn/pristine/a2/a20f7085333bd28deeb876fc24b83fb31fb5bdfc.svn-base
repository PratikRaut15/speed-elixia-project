-- Insert SQL here.

ALTER TABLE `stock` ADD `cartons` INT(10) DEFAULT NULL DEFAULT '0' AFTER `is_asm`;
ALTER TABLE `stock` ADD `srid` INT(11) NOT NULL AFTER `stockid`;
ALTER TABLE `style` ADD `carton` INT(11) NOT NULL DEFAULT '0' AFTER `retailprice`;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 10, NOW(), 'Ganesh','Order');




