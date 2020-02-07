-- Insert SQL here.
ALTER TABLE `customer` ADD `temp_sesors` INT( 11 ) NOT NULL ;
ALTER TABLE `customer` CHANGE `temp_sesors` `temp_sensors` INT( 11 ) NOT NULL ;

ALTER TABLE `vehicle` ADD `temp1_min` INT( 11 ) NOT NULL ,
ADD `temp1_max` INT( 11 ) NOT NULL ,
ADD `temp2_min` INT( 11 ) NOT NULL ,
ADD `temp2_max` INT( 11 ) NOT NULL ;

ALTER TABLE `eventalerts` ADD `temp2` BOOL NOT NULL AFTER `temp` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 83, NOW(), 'Sanket Sheth','Temperature Sensors');
