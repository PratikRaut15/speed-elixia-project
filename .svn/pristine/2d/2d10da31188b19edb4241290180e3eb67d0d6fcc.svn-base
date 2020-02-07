-- Insert SQL here.

ALTER TABLE `customer` ADD `use_creditcard` BOOL NOT NULL ;
ALTER TABLE `customer` ADD `use_forms` BOOL NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 32, NOW(), 'Sanket Sheth','Credit Card');