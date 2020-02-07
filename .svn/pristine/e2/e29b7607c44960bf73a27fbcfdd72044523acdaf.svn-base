ALTER TABLE `maintenance_history`  ADD `isdeleted` TINYINT(1) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (305, NOW(), 'Sahil','isdeleted  alter in maintenance_history');
