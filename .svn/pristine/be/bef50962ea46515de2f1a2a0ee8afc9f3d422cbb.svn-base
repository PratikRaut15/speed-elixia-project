-- Insert SQL here.

ALTER TABLE `user`  ADD `lastlogin_android` DATETIME NOT NULL;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 55, NOW(), 'Ajay Tripathi','last login android');
