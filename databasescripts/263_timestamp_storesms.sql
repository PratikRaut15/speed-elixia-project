-- Insert SQL here.

ALTER TABLE `storesms`  ADD `timestamp` datetime NOT NULL AFTER message ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (263, NOW(), 'Shrikanth Suryawanshi','timestamp for storesms');
