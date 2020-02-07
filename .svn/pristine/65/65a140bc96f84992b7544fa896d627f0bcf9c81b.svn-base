-- Insert SQL here.

CREATE TABLE  `state` (
`stateid` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`nationid` INT( 11 ) NOT NULL ,
`code` VARCHAR( 225 ) NOT NULL ,
`address` TEXT NOT NULL ,
`name` VARCHAR( 225 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`isdeleted` TINYINT( 1 ) NOT NULL ,
`timestamp` DATETIME NOT NULL ,
PRIMARY KEY (  `stateid` )
) ENGINE = MYISAM ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 58, NOW(), 'Ajay Tripathi','State Table');
