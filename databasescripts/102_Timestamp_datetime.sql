-- Insert SQL here.

ALTER TABLE `vehicle` CHANGE `timestamp` `timestamp` DATETIME NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 102, NOW(), 'Sanket Sheth','Timestamp DateTime');
