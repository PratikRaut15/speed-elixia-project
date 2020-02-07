-- Insert SQL here.


UPDATE `probity_master` SET `workkey_name` = 'Padgha To Vidhyalaya Road', `customerno` = '15' WHERE `probity_master`.`pmid` = 5;




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 238, NOW(), 'Shrikant Suryawanshi','probity updates');
