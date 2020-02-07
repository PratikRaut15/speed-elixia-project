-- Insert SQL here.
ALTER TABLE `support`
ADD `email` VARCHAR(50) NOT NULL AFTER `userid`,
ADD `phone` VARCHAR(15) NOT NULL AFTER `email`;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 114, NOW(), 'Shrikanth Suryawanshi', '114-Add Email Phone To Support Issue');
