

-- Insert SQL here.

CREATE TABLE `account_switch` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`customerno` INT NOT NULL ,
`childid` INT NOT NULL
) ENGINE = MYISAM ;



-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 130, NOW(), 'Shrikanth Suryawanshi','Account Switch');