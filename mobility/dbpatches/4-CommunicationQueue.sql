-- Insert SQL here.

ALTER TABLE `communicationhistory` ADD `customerno` INT( 11 ) NOT NULL ;
ALTER TABLE `communicationqueue` ADD `customerno` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 4, NOW(), 'Sanket Sheth','Communication Queue');