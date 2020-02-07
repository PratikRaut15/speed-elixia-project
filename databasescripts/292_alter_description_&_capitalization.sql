ALTER TABLE `capitalization`  ADD `deprication_amt` DECIMAL(11,2) NOT NULL  AFTER `cost`;

ALTER TABLE `description`  ADD `legacycode` INT(7) NOT NULL ,  ADD `assetcode` VARCHAR(21) NOT NULL ,  ADD `assetid` INT(7) NOT NULL ,  ADD `vehiclevendor_id` INT(7) NOT NULL ;

ALTER TABLE `description`  ADD `manufacturercode` VARCHAR(9) NOT NULL ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (292, NOW(), 'Sahil Gandhi','alter in capitalization & description');
