
ALTER TABLE `primary_order` ADD `deliverydate` DATETIME NOT NULL AFTER `distributorid`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 15, NOW(), 'Ganesh','Primary sales delivery date add');
