-- Insert SQL here.
ALTER TABLE `vehicle` DROP `devicekey`;
DROP TABLE `checkpointreport`;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 1, NOW(), 'Chirag Jain','Dropping Unwanted Fields');
