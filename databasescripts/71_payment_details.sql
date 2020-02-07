-- Insert SQL here.

CREATE TABLE  `payment` (
`payment_id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` TINYINT( 1 ) NOT NULL ,
`amount` VARCHAR( 250 ) NOT NULL ,
`chequeno` VARCHAR( 250 ) NOT NULL ,
`cardno` VARCHAR( 250 ) NOT NULL ,
`exp_date` DATE NOT NULL ,
`card_name` VARCHAR( 250 ) NOT NULL
) ENGINE = MYISAM ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 71, NOW(), 'Ajay Tripathi','payment table');
