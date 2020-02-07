-- Insert SQL here.
ALTER TABLE `unit`  ADD `onlease` TINYINT(1) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (273, NOW(), 'Sahil','Alter Unit table');
