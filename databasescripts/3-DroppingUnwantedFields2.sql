-- Insert SQL here.

ALTER TABLE `unit` DROP `ac_status` ;

ALTER TABLE `devices` DROP `ignition_last_status` ,
DROP `ignition_last_check` ,
DROP `ignition_email_status` ;

DROP TABLE `ac_sensor` ;

ALTER TABLE `eventalerts` DROP `ignition` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 3, NOW(), 'Sanket Sheth','DroppingUnwantedFields2');
