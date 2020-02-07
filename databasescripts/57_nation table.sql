-- Insert SQL here.

CREATE TABLE  `nation` (
`nationid` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`code` VARCHAR( 225 ) NOT NULL ,
`address` TEXT NOT NULL ,
`name` VARCHAR( 225 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`isdeleted` TINYINT( 1 ) NOT NULL ,
`timestamp` DATETIME NOT NULL ,
PRIMARY KEY (  `nationid` )
) ENGINE = MYISAM ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 57, NOW(), 'Ajay Tripathi','Nation Table');
