-- Insert SQL here.
ALTER TABLE `unit`  ADD `tempsen3` INT(11) NOT NULL  AFTER `tempsen2`,  ADD `tempsen4` INT(11) NOT NULL  AFTER `tempsen3`;

ALTER TABLE `vehicle`  ADD `temp3_min` INT(11) NOT NULL  AFTER `temp2_max`,  ADD `temp3_max` INT(11) NOT NULL  AFTER `temp3_min`,  ADD `temp4_min` INT(11) NOT NULL  AFTER `temp3_max`,  ADD `temp4_max` INT(11) NOT NULL  AFTER `temp4_min`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 276, NOW(), 'Sahil','temp related team changes');
