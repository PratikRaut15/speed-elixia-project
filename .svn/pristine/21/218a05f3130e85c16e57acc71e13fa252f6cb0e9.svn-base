-- Insert SQL here.

ALTER TABLE `unit` DROP `monthlysub_cost`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 244, NOW(), 'Gaensh','Drop column monthlysub_cost');
