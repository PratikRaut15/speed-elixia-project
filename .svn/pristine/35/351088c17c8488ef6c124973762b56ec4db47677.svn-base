ALTER TABLE `maintenance`  ADD `battery_srno` VARCHAR(15) NOT NULL  AFTER `tyre_type`;

ALTER TABLE `maintenance_history`  ADD `battery_srno` VARCHAR(15) NOT NULL  AFTER `tyre_type`;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 290, NOW(), 'Sahil Gandhi','alter_batterysrno_maintenance');
