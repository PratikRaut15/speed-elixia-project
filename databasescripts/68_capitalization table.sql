-- Insert SQL here.

CREATE TABLE  `capitalization` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`address` TEXT NOT NULL ,
`cost` VARCHAR( 225 ) NOT NULL ,
`date` DATE NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 68, NOW(), 'Ajay Tripathi','capitalization');
