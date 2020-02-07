-- Insert SQL here.

ALTER TABLE `distributor` ADD `dob` DATE NOT NULL AFTER `distcode`;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 13, NOW(), 'Ganesh','Distributor dob');




