

ALTER TABLE `style` ADD `productimage` VARCHAR(100) NOT NULL AFTER `carton`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 18, NOW(), 'Ganesh','add column productimg ');
