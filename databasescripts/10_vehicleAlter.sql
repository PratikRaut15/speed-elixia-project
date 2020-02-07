-- Insert SQL here.
ALTER TABLE `vehicle` CHANGE `brand` `description` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 10, NOW(), 'Ajay Tripathi','Replace Brand column with Description in vehicle table');
