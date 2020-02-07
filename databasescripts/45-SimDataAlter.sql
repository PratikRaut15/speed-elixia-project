-- Insert SQL here.

ALTER TABLE `simdata`  ADD `lat` FLOAT NOT NULL AFTER `client`,  ADD `long` FLOAT NOT NULL AFTER `lat`,  ADD `system_msg` TEXT NOT NULL AFTER `long`,  ADD `customerno` INT(11) NOT NULL AFTER `system_msg`,  ADD `vehicleid` INT(11) NOT NULL AFTER `customerno`,  ADD `success` TINYINT(1) NOT NULL AFTER `vehicleid`;

ALTER TABLE `customer`  ADD `smspwd` VARCHAR(50) NOT NULL;

UPDATE `customer` SET `smspwd` = CONCAT("", 'CUST', "", customerno, "");

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 45, NOW(), 'Ajay Tripathi','Sim Data');
