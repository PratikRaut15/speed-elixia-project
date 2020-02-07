-- Insert SQL here.

ALTER TABLE `customer` ADD `use_tms` TINYINT(1) NOT NULL AFTER `use_sales`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 281, NOW(), 'Ganesh','Add use_tms');
