-- Insert SQL here.

CREATE TABLE  `notes` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`notes` TEXT NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`status` VARCHAR( 250 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

ALTER TABLE  `notes` CHANGE  `status`  `status` TINYINT( 1 ) NOT NULL;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (91, NOW(), 'ajay','notes table');
