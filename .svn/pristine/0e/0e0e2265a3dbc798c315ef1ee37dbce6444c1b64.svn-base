-- Insert SQL here.

ALTER TABLE `user` ADD `gcmid` TEXT NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 159, NOW(), 'Sanket Sheth','GCM');
