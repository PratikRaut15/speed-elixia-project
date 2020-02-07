-- Insert SQL here.

CREATE TABLE  `maintenance` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`maintenance_date` DATE NOT NULL ,
`meter_reading` INT( 10 ) NOT NULL ,
`vehicle_in_date` DATE NOT NULL ,
`vehicle_out_date` DATE NOT NULL ,
`dealer_id` INT( 11 ) NOT NULL ,
`invoice_date` DATE NOT NULL ,
`invoice_no` VARCHAR( 250 ) NOT NULL ,
`invoice_amount` VARCHAR( 250 ) NOT NULL ,
`payment_id` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`notes` VARCHAR( 250 ) NOT NULL ,
`tyre_type` TINYINT( 1 ) NOT NULL ,
`category` TINYINT( 1 ) NOT NULL ,
`statusid` TINYINT( 1 ) NOT NULL
) ENGINE = MYISAM ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 73, NOW(), 'Ajay Tripathi','maintenance table');
