-- Insert SQL here.


ALTER TABLE `team` ADD `rid` INT(20) NOT NULL AFTER `teamid`;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 223, NOW(), 'Ganesh','alter team for crm');
