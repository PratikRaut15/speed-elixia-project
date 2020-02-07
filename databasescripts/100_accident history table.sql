-- Insert SQL here.

CREATE TABLE `accident_history` (
`acc_hist_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`accidentid` INT( 11 ) NOT NULL ,
`accident_datetime` DATETIME NOT NULL ,
`accident_location` VARCHAR( 225 ) NOT NULL ,
`thirdparty_insured` TINYINT( 1 ) NOT NULL ,
`description` TEXT NOT NULL ,
`drivername` VARCHAR( 225 ) NOT NULL ,
`licence_validity_from` DATE NOT NULL ,
`licence_validity_to` DATE NOT NULL ,
`licence_type` VARCHAR( 225 ) NOT NULL ,
`workshop_location` VARCHAR( 225 ) NOT NULL ,
`loss_amount` INT( 11 ) NOT NULL ,
`sett_amount` INT( 11 ) NOT NULL ,
`actual_amount` INT( 11 ) NOT NULL ,
`mahindra_amount` INT( 11 ) NOT NULL ,
`send_report_to` VARCHAR( 100 ) NOT NULL ,
`vehicle_in_time` DATE NOT NULL ,
`vehicle_out_time` DATE NOT NULL ,
`meter_reading` INT( 10 ) NOT NULL ,
`approval_notes` VARCHAR( 225 ) NOT NULL ,
`statusid` TINYINT( 1 ) NOT NULL ,
`roleid` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`isdeleted` TINYINT( 1 ) NOT NULL ,
`timestamp` DATETIME NOT NULL ,
PRIMARY KEY ( `acc_hist_id` )
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 100, NOW(), 'Ajay Tripathi','accident history table');
