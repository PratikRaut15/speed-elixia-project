ALTER TABLE `fuelstorrage`  ADD `isdeleted` TINYINT(1) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (300, NOW(), 'Sahil Gandhi','alter fuelstorrage');
