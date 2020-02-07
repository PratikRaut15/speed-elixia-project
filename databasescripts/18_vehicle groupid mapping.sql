-- Insert SQL here.



ALTER TABLE `vehicle`  ADD `groupid` TINYINT(4) NOT NULL DEFAULT '0';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 18, NOW(), 'Ajay Tripathi','added groupid to vehicle table');