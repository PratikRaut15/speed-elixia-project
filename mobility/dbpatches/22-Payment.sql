-- Insert SQL here.

CREATE TABLE `payment` (
`paymentid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`serviceid` INT( 11 ) NOT NULL ,
`type` INT( 11 ) NOT NULL ,
`is_partial` BOOL NOT NULL ,
`partial_amt` FLOAT NOT NULL ,
`chequeno` INT( 11 ) NOT NULL ,
`accountno` INT( 11 ) NOT NULL ,
`branch` VARCHAR( 50 ) NOT NULL ,
`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 22, NOW(), 'Sanket Sheth','Payment');