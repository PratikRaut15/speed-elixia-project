-- Insert SQL here.
ALTER TABLE  `dealer` CHANGE  `vendor`  `vendor` INT( 11 ) NOT NULL;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (90, NOW(), 'ajay','alert dealer for vendor field');
