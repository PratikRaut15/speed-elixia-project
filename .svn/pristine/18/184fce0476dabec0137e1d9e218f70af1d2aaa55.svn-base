-- Insert SQL here.

CREATE TABLE `trans_status` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`status` VARCHAR( 50 ) NOT NULL ,
`type` BOOL NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `trans_status` (
`id` ,
`status` ,
`type`
)
VALUES (
NULL , 'Fresh', '0'
), (
NULL , 'Ready', '0'
), (
NULL , 'Bad', '0'
), (
NULL , 'Repaired', '0'
), (
NULL , 'With Customer', '0'
), (
NULL , 'Suspect at Customer', '0'
), (
NULL , 'Under Repair', '0'
), (
NULL , 'Repaired', '0'
), (
NULL , 'Replaced', '0'
), (
NULL , 'Terminated', '0'
), (
NULL , 'Activated', '1'
), (
NULL , 'Bad', '1'
), (
NULL , 'With Customer', '1'
), (
NULL , 'Suspect at Customer', '1'
), (
NULL , 'Apply for Disconnection', '1'
), (
NULL , 'Disconnected', '1'
);

CREATE TABLE `simcard` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`simcardno` INT( 50 ) NOT NULL ,
`vendorid` INT( 11 ) NOT NULL ,
`trans_statusid` INT( 11 ) NOT NULL ,
`teamid` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `vendor` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vendorname` VARCHAR( 50 ) NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `vendor` (
`id` ,
`vendorname`
)
VALUES (
NULL , 'Bharti Airtel'
), (
NULL , 'Idea'
);

CREATE TABLE `trans_history` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` BOOL NOT NULL ,
`simcard_id` INT( 11 ) NOT NULL ,
`unitno` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`invoiceno` VARCHAR( 50 ) NOT NULL ,
`trans_statusid` INT( 11 ) NOT NULL ,
`teamid` INT( 11 ) NOT NULL ,
`trans_time` DATETIME NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `unit` ADD `trans_statusid` INT( 11 ) NOT NULL ;

UPDATE unit SET trans_statusid = 5;

ALTER TABLE `simcard` ADD `customerno` INT( 11 ) NOT NULL ;

ALTER TABLE `devices` ADD `expirydate` DATE NOT NULL ;

ALTER TABLE `simcard` DROP `teamid` ;

ALTER TABLE `devices` ADD `invoiceno` VARCHAR( 50 ) NOT NULL ;

ALTER TABLE `simcard` CHANGE `simcardno` `simcardno` VARCHAR( 50 ) NOT NULL ;

ALTER TABLE `devices` ADD `simcardid` INT( 11 ) NOT NULL ;

INSERT INTO `trans_status` (
`id` ,
`status` ,
`type`
)
VALUES (
NULL , 'Suspect at Elixia', '0'
);

UPDATE `trans_status` SET `status` = 'Suspected at Elixia Tech!' WHERE `trans_status`.`id` =17;

DELETE FROM trans_status WHERE id = 8;

ALTER TABLE `trans_history`
  DROP `customerno`,
  DROP `invoiceno`,
  DROP `trans_statusid`;

ALTER TABLE `trans_history` ADD `transaction` TEXT NOT NULL ;

ALTER TABLE `trans_history` ADD `customerno` INT( 11 ) NOT NULL ,
ADD `statusid` INT( 11 ) NOT NULL ;

ALTER TABLE `trans_history` CHANGE `unitno` `unitid` INT( 11 ) NOT NULL ;

ALTER TABLE `trans_history` CHANGE `id` `thid` INT( 11 ) NOT NULL AUTO_INCREMENT ;

ALTER TABLE `trans_history` ADD `simcardno` VARCHAR( 50 ) NOT NULL ,
ADD `invoiceno` VARCHAR( 50 ) NOT NULL ,
ADD `expirydate` VARCHAR( 50 ) NOT NULL ;

ALTER TABLE `trans_history` CHANGE `expirydate` `expirydate` DATE NOT NULL ;

ALTER TABLE `trans_history` CHANGE `type` `type` INT( 11 ) NOT NULL ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 28, NOW(), 'Sanket Sheth','Team Changes');
