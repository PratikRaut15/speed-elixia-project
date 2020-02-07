-- Insert SQL here.

 ALTER TABLE `user`  ADD `chgpwd` TINYINT(4) NOT NULL,  ADD `chgalert` TINYINT(4) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 15, NOW(), 'Ajay Tripathi','Change pwd at 1st login');
