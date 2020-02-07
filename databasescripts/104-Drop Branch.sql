-- Insert SQL here.

DROP TABLE `branch` ;
ALTER TABLE `user` DROP `active_branchid` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 104, NOW(), 'Sanket Sheth','Drop Branch');
