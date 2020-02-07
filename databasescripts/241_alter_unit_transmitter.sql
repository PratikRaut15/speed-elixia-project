-- Insert SQL here.

ALTER TABLE `unit` ADD `transmitterno` VARCHAR(20) NOT NULL AFTER `is_genset_opp`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 241, NOW(), 'Ganesh Papde','alter unit transmitter');
