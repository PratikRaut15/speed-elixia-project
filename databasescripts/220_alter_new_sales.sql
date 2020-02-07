-- Insert SQL here.

ALTER TABLE `new_sales` 
  DROP `analog1_sen`,
  DROP `analog2_sen`,
  DROP `analog3_sen`,
  DROP `analog4_sen`;
ALTER TABLE `new_sales` ADD `tempsen` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_door_opp`;
ALTER TABLE `new_sales` ADD `new_customer` VARCHAR(50) NOT NULL AFTER `customerno`;
ALTER TABLE `new_sales` ADD `install_device_qty` INT(20) NOT NULL AFTER `deviceqty`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 220, NOW(), 'Ganesh','alter New sales');
