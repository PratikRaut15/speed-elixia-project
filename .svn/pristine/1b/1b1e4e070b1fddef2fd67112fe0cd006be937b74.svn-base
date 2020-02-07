ALTER TABLE `fuelstorrage`  ADD `perdaykm` VARCHAR(25) NOT NULL  AFTER `addedon`,  ADD `refilldate` DATE NOT NULL  AFTER `perdaykm`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (308, NOW(), 'Sahil','alter in fuel storage');




