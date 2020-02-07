-- Insert SQL here.

ALTER TABLE `stock` ADD `is_approved` TINYINT(5) NOT NULL DEFAULT '0' AFTER `is_asm`;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 12, NOW(), 'Ganesh','stock add isapproved');




