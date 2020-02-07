-- Insert SQL here.


ALTER TABLE `user`  ADD `thhistory` TINYINT(1) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 21, NOW(), 'Ajay Tripathi','added travel history email alert to user table');