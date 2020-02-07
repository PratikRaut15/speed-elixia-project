-- Insert SQL here.

CREATE TABLE  `dealer` (
`dealerid` INT( 11 ) NOT NULL  AUTO_INCREMENT,
`name` VARCHAR( 225 ) NOT NULL ,
`address` TEXT NOT NULL ,
`city` VARCHAR( 225 ) NOT NULL ,
`state` INT( 11 ) NOT NULL ,
`phone` VARCHAR( 225 ) NOT NULL ,
`cellphone` VARCHAR( 225 ) NOT NULL ,
`notes` VARCHAR( 225 ) NOT NULL ,
`vendor` VARCHAR( 225 ) NOT NULL ,
`groupid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`isdeleted` TINYINT( 1 ) NOT NULL ,
`timestamp` DATETIME NOT NULL,
  PRIMARY KEY (`dealerid`)
) ENGINE = MYISAM ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 56, NOW(), 'Ajay Tripathi','Dealer Table');
