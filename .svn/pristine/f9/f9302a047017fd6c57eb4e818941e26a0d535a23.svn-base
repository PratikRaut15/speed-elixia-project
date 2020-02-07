-- Insert SQL here.
ALTER TABLE `discount_specific` ADD `cityid` INT(11) default NULL AFTER `locationid`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 23, NOW(), 'Ganesh','Add cityid');



