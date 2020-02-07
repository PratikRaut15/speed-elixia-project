-- Insert SQL here.

CREATE TABLE  `tax` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`from_date` DATE NOT NULL ,
`to_date` DATE NOT NULL ,
`amount` VARCHAR( 225 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`type` TINYINT( 1 ) NOT NULL
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 77, NOW(), 'Ajay Tripathi','tax table');
