

-- Insert SQL here.

CREATE TABLE `login_history` (
`loginid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`type` TINYINT( 1 ) NOT NULL ,
`timestamp` DATETIME NOT NULL
) ENGINE = MYISAM ;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 133, NOW(), 'Shrikanth Suryawanshi','User Login History');