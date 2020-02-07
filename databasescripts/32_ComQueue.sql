-- Insert SQL here.

CREATE TABLE `comqueue` (
`cqid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`customerno` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`lat` FLOAT NOT NULL ,
`long` FLOAT NOT NULL ,
`type` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `comqueue` ADD `timeadded` DATETIME NOT NULL ;

ALTER TABLE `comqueue` ADD `status` BOOL NOT NULL ;

CREATE TABLE `comhistory` (
`cqhid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cqid` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`comtype` BOOL NOT NULL ,
`type` INT( 11 ) NOT NULL ,
`status` BOOL NOT NULL ,
`lat` FLOAT NOT NULL ,
`long` FLOAT NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL ,
`timeadded` DATETIME NOT NULL ,
`timesent` DATETIME NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `comhistory` ADD `message` TEXT NOT NULL ;

ALTER TABLE `comhistory` ADD `userid` INT( 11 ) NOT NULL AFTER `customerno` ;

ALTER TABLE `comhistory` DROP `message` ;

ALTER TABLE `comhistory` DROP `cqid` ;

DROP TABLE `alerthistory` ;

ALTER TABLE `comqueue` ADD `message` TEXT NOT NULL ;

ALTER TABLE `comqueue` ADD `processed` BOOL NOT NULL ;

ALTER TABLE `comqueue` ADD `is_shown` BOOL NOT NULL ;

ALTER TABLE `comqueue` ADD `chkid` INT( 11 ) NOT NULL ,
ADD `fenceid` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 32, NOW(), 'Sanket Sheth','Com_Queue');
