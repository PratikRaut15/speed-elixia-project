-- Insert SQL here.

ALTER TABLE `checkpoint` ADD `eta` TIME NOT NULL AFTER `userid` ;

ALTER TABLE `checkpoint` ADD `eta_starttime` DATETIME NOT NULL AFTER `eta`;

CREATE TABLE `etamanage` (
`etaid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`checkpointid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`eta` TIME NOT NULL ,
`starttime` DATETIME NOT NULL ,
`endtime` DATETIME NOT NULL ,
`timestamp` DATETIME NOT NULL
) ENGINE = MYISAM ;




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 125, NOW(), 'Shrikanth Suryawanshi','Checkpoint ETA');
