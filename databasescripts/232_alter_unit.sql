-- Insert SQL here.

ALTER TABLE `unit` CHANGE `fuelsensor` `fuelsensor` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `unit` ADD `tempsen1` INT(11) NOT NULL AFTER `fuelsensor`, ADD `tempsen2` INT(11) NOT NULL AFTER `tempsen1`;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 232, NOW(), 'Ganesh Papde','alter unit table fuel sensor /tempsen1/tempsen2');
