-- Insert SQL here.

ALTER TABLE  `customfield` ADD  `custom_id` INT NOT NULL AFTER  `name`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 64, NOW(), 'Ajay Tripathi','Custom Field');
