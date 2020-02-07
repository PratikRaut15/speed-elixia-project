
ALTER TABLE `master_orders`  ADD `delivery_lat` VARCHAR(20) NOT NULL  AFTER `longi`,  ADD `delivery_long` VARCHAR(20) NOT NULL  AFTER `delivery_lat`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 26, NOW(), 'Ganesh Papde','delivery lat long cols add');


