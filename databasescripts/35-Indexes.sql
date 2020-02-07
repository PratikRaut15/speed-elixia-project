-- Insert SQL here.

ALTER TABLE `checkpointmanage` ADD INDEX ( `checkpointid` ) ;
ALTER TABLE `fenceman` ADD INDEX ( `fenceid` ) ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 35, NOW(), 'Sanket Sheth','Team Changes');
