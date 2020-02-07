-- Insert SQL here.

ALTER TABLE `unit` ADD `is_ac_opp` BOOLEAN NOT NULL AFTER `acsensor` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 5, NOW(), 'Sanket Sheth','AC Opp.');
