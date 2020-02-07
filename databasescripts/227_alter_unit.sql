-- Insert SQL here.

ALTER TABLE `unit` ADD `is_twowaycom` TINYINT(1) NOT NULL AFTER `is_mobiliser`, ADD `is_portable` TINYINT(1) NOT NULL AFTER `is_twowaycom`;
ALTER TABLE `unit` ADD `gensetsensor` TINYINT(1) NOT NULL AFTER `is_ac_opp`, ADD `is_genset_opp` TINYINT(1) NOT NULL AFTER `gensetsensor`;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 227, NOW(), 'Gaensh','add genset sensor column');
