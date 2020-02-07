-- Insert SQL here.
ALTER TABLE `vehicle` CHANGE `groupid` `groupid` INT( 11 ) NOT NULL DEFAULT '0';

UPDATE user SET roleid =5 WHERE role = 'Administrator';
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 123, NOW(), 'Shrikanth Suryawanshi','Set Role Id and Group Id');
