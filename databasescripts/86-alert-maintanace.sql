-- Insert SQL here.

ALTER TABLE `maintenance` ADD `file_name` VARCHAR( 255 ) NOT NULL AFTER `notes` ;
ALTER TABLE `maintenance` ADD `amount_quote` INT NOT NULL AFTER `notes` ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 86, NOW(), 'vishu','alter maintance');
