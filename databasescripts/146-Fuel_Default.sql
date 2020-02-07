-- Insert SQL here.

ALTER TABLE `vehicle` CHANGE `fuel_balance` `fuel_balance` FLOAT( 5, 2 ) NULL DEFAULT '0.00';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 146, NOW(), 'Sanket Sheth','Timestamp DateTime');
