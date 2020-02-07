-- Insert SQL here.

ALTER TABLE `unit` ADD `issue` VARCHAR(100) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 224, NOW(), 'Ganesh','alter unit issue');
