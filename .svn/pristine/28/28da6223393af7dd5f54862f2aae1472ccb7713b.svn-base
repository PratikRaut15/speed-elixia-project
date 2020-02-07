-- Insert SQL here.

ALTER TABLE `tripdetails` ADD `statusdate` DATETIME NULL DEFAULT NULL AFTER `tripstatusid`;

ALTER TABLE `tripdetail_history` ADD `statusdate` DATETIME NULL DEFAULT NULL AFTER `tripstatusid`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 314, NOW(), 'Shrikant Suryawanshi','Trips-Add DateTime');
