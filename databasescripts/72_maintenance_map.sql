-- Insert SQL here.

CREATE TABLE  `maintenance_map` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` TINYINT( 1 ) NOT NULL ,
`maintenance_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 72, NOW(), 'Ajay Tripathi','maintenance map table');
