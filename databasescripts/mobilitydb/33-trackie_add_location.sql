-- Insert SQL here.

-- Successful. Add the Patch to the Applied Patches table.

ALTER TABLE `trackie` ADD `locid` INT(10) DEFAULT NULL AFTER `weekly_off`;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 33, NOW(), 'Ganesh','add locationid');
