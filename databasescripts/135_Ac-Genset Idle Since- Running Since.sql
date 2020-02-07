

-- Insert SQL here.

ALTER TABLE `unit` ADD `digitalioupdated` DATETIME NOT NULL AFTER `digitalio` ;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 135, NOW(), 'Shrikanth Suryawanshi',' Ac genset idle since running since');