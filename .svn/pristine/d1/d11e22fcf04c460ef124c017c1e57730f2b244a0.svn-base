-- Insert SQL here.

ALTER TABLE `customer` ADD `use_sales` TINYINT(1) NOT NULL AFTER `use_secondary_sales`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 280, NOW(), 'Ganesh','Add sales engagement');
