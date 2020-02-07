-- Insert SQL here.

ALTER TABLE `customer` ADD `use_portable` TINYINT( 1 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 117, NOW(), 'Sanket Sheth','Use Portable');
