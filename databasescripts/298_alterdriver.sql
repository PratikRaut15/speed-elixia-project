ALTER TABLE `driver`  ADD `trip_phone` VARCHAR(21) NOT NULL ;
ALTER TABLE `driver`  ADD `istripstarted` TINYINT(1) NOT NULL ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (298, NOW(), 'Sahil Gandhi','alter driver');
