-- Insert SQL here.

ALTER TABLE  `vehicle` ADD  `modelid` INT( 11 ) NOT NULL ,
ADD  `manufacturing_year` INT( 5 ) NOT NULL ,
ADD  `purchase_date` DATE NOT NULL ,
ADD  `start_meter_reading` INT( 10 ) NOT NULL ,
ADD  `fueltype` VARCHAR( 225 ) NOT NULL ,
ADD  `is_insured` TINYINT( 1 ) NOT NULL;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 75, NOW(), 'Ajay Tripathi','vehicle table alter');
