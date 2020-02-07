-- Insert SQL here.

ALTER TABLE `new_sales` ADD `is_twowaycom` TINYINT(1) NOT NULL AFTER `is_mobiliser`, ADD `is_portable` TINYINT(1) NOT NULL AFTER `is_twowaycom`;
ALTER TABLE `new_sales` ADD `gensetsensor` TINYINT(1) NOT NULL AFTER `is_ac_opp`;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 228, NOW(), 'Gaensh','alter new_sales add genset');
