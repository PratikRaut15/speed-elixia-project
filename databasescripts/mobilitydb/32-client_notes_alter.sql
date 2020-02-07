-- Insert SQL here.

-- Successful. Add the Patch to the Applied Patches table.

ALTER TABLE `client` ADD `notes` TEXT default NULL;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 32, NOW(), 'Ganesh','add notes');
