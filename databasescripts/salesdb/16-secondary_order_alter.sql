ALTER TABLE `secondary_order` ADD `orderdate` DATETIME NOT NULL AFTER `shopid`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 15, NOW(), 'Ganesh','Primary sales delivery date add');
