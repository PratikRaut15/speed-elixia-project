-- Insert SQL here.
UPDATE `status` SET `status` = 'AC Sensor' WHERE `status`.`id` = 1;
UPDATE `status` SET `status` = 'Temperature sensor' WHERE `status`.`id` = 8;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 40, NOW(), 'Ajay Tripathi','Alter Status Table');
