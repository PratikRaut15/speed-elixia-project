CREATE TABLE `checklist_form` (
`ftid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fname` VARCHAR( 255 ) NOT NULL ,
`userid` INT NOT NULL ,
`customerno` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;






CREATE TABLE `checklist_meta_data` (
`mdid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ftid` INT NOT NULL ,
`textfields` INT NOT NULL ,
`checkbox` INT NOT NULL ,
`dropdown` INT NOT NULL ,
`userid` INT NOT NULL ,
`customerno` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;




CREATE TABLE `checklist_data_table` (
`dtid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`mdid` INT NOT NULL ,
`lable` VARCHAR( 255 ) NOT NULL ,
`type` INT NOT NULL ,
`customerno` INT NOT NULL ,
`userid` INT NOT NULL ,
`priority` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;





CREATE TABLE `checklist_option_table` (
`oid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`dtid` INT NOT NULL ,
`option_name` VARCHAR( 255 ) NOT NULL ,
`customerno` INT NOT NULL ,
`userid` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;







ALTER TABLE `checklist_form` ADD `isdeleted` BOOL NOT NULL ;

ALTER TABLE `checklist_option_table` ADD `ftid` INT NOT NULL ;























 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 18, NOW(), 'vishwanath','checklist');