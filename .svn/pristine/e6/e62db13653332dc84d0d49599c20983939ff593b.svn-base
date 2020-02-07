-- Insert SQL here.

CREATE TABLE `temp_compliance` (`temp_compliance_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `uid` INT(11) NOT NULL, `vehicleid` INT(11) NOT NULL, `bc1` INT(11) NOT NULL, `gc_c1` INT(11) NOT NULL, `gc_nc1` INT(11) NOT NULL, `min_1` VARCHAR(5) NOT NULL, `max_1` VARCHAR(5) NOT NULL, `min_range_1` INT(11) NOT NULL, `max_range_1` INT(11) NOT NULL, `bc2` INT(11) NOT NULL, `gc_c2` INT(11) NOT NULL, `gc_nc2` INT(11) NOT NULL, `min_2` VARCHAR(5) NOT NULL, `max_2` VARCHAR(5) NOT NULL, `min_range_2` INT(11) NOT NULL, `max_range_2` INT(11) NOT NULL, `bc3` INT(11) NOT NULL, `gc_c3` INT(11) NOT NULL, `gc_nc3` INT(11) NOT NULL, `min_3` VARCHAR(5) NOT NULL, `max_3` VARCHAR(5) NOT NULL, `min_range_3` INT(11) NOT NULL, `max_range_3` INT(11) NOT NULL, `bc4` INT(11) NOT NULL, `gc_c4` INT(11) NOT NULL, `gc_nc4` INT(11) NOT NULL, `min_4` VARCHAR(5) NOT NULL, `max_4` VARCHAR(5) NOT NULL, `min_range_4` INT(11) NOT NULL, `max_range_4` INT(11) NOT NULL, `timestamp` DATETIME NOT NULL) ENGINE = InnoDB;
ALTER TABLE `temp_compliance` ADD `customerno` INT( 11 ) NOT NULL ;
ALTER TABLE `temp_compliance` ADD `range_change_timestamp` DATETIME NOT NULL ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 354, NOW(), 'Sanket Sheth','Temp_Compliance_Table');
