-- Insert SQL here.

ALTER TABLE `client` ADD `cityid` INT(11) NOT NULL AFTER `landmark`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 14, NOW(), 'ganesh','Client table altered');



