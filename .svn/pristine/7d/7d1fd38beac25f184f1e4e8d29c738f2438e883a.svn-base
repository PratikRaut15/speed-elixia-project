-- Insert SQL here.

ALTER TABLE `servicecall` CHANGE `vehicleid` `vehicleno` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `servicecall` ADD `type` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 142, NOW(), 'Sanket Sheth','Service Call Changes');
