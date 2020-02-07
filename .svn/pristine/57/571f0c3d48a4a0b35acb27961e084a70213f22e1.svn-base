-- Insert SQL here.

ALTER TABLE `customer` ADD `use_delivery` INT( 1 ) NOT NULL AFTER `use_hierarchy` ;



-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 143, NOW(), 'Shrikanth Suryawanshi', 'Use Delivery');