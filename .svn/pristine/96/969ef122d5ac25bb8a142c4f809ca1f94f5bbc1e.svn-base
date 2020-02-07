-- Insert SQL here.


ALTER TABLE `user`  ADD `groupid` TINYINT(4) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 20, NOW(), 'Ajay Tripathi','added groupid to user table');