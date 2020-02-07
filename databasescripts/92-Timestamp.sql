-- Insert SQL here.
ALTER TABLE `vehicle` ADD `timestamp` DATETIME NOT NULL ;
ALTER TABLE `vehicle` CHANGE `timestamp` `timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;

ALTER TABLE `tax` ADD `userid` INT( 11 ) NOT NULL ,
ADD `timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
ADD `isdeleted` INT( 11 ) NOT NULL ,
ADD `customerno` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 92, NOW(), 'Sanket Sheth','Timestamp in Vehicle Table');
