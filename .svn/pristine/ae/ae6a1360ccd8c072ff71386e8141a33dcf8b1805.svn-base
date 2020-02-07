-- Insert SQL here.
ALTER TABLE `checkpoint` ADD INDEX(`checkpointid`);
ALTER TABLE `checkpointmanage` ADD INDEX(`vehicleid`);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 38, NOW(), 'Ajay Tripathi','Index for checkpoint cron');
