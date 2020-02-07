-- Insert SQL here.

CREATE TABLE `support` (
`supportid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` INT( 11 ) NOT NULL ,
`note` TEXT NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`timestamp` DATETIME NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`is_closed` TINYINT( 1 ) NOT NULL ,
`closenotes` TEXT NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `support` ADD `close_timestamp` DATETIME NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 106, NOW(), 'Sanket Sheth','Support');