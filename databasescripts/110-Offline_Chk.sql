-- Insert SQL here.

CREATE TABLE `chk_offline` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`customerno` INT( 11 ) NOT NULL ,
`lastupdated` DATETIME NOT NULL ,
`vehicleid` INT NOT NULL ,
`latitude` FLOAT NOT NULL ,
`longitude` FLOAT NOT NULL
) ENGINE = MYISAM ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 110, NOW(), 'Sanket Sheth','Offline CHK');
