-- Insert SQL here.

ALTER TABLE customer add use_warehouse tinyint(1) NOT NULL AFTER use_extradigital;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 254, NOW(), 'Shrikant Suryawanshi','Use Warehouse');
