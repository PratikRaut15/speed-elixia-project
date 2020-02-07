-- Insert SQL here.

CREATE TABLE  `description` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vehicleid` INT( 11 ) NOT NULL ,
`engineno` VARCHAR( 225 ) NOT NULL ,
`chasisno` VARCHAR( 225 ) NOT NULL ,
`vehicle_purpose` TINYINT( 1 ) NOT NULL ,
`vehicle_type` TINYINT( 1 ) NOT NULL ,
`reg_no` VARCHAR( 225 ) NOT NULL ,
`reg_date` DATE NOT NULL ,
`dealerid` INT( 11 ) NOT NULL ,
`invoiceno` VARCHAR( 225 ) NOT NULL ,
`invoicedate` DATE NOT NULL
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 76, NOW(), 'Ajay Tripathi','description table');
