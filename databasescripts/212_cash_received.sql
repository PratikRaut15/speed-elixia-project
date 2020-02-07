-- Insert SQL here.

ALTER TABLE `cash_received` ADD `isreturn` TINYINT(1) NOT NULL DEFAULT '0' ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 212, NOW(), 'Ganesh','return money');
