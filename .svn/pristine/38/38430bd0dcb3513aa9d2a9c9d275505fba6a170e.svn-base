-- Insert SQL here.
CREATE TABLE `notifications` (
`notid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`notification` TEXT NOT NULL ,
`type` INT( 11 ) NOT NULL ,
`isnotified` BOOL NOT NULL ,
`customerno` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `communicationqueue` DROP `is_notif` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 23, NOW(), 'Sanket Sheth','Notifications');
