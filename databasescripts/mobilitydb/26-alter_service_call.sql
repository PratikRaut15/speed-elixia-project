-- Insert SQL here.
ALTER TABLE `service_list` ADD `cid` INT(11) NOT NULL AFTER `customerno`;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 26, NOW(), 'Gaensh','Service Call add category id');



