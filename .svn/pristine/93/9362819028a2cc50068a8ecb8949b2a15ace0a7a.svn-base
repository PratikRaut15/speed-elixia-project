-- Insert SQL here.

ALTER TABLE `payment` ADD `customerno` INT( 11 ) NOT NULL ;
ALTER TABLE `payment` ADD `sqlitepaymentid` INT( 11 ) NOT NULL AFTER `serviceid` ;
ALTER TABLE `payment` CHANGE `is_web` `is_web` BOOL NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 29, NOW(), 'Sanket Sheth','Customerno_In_Payment');