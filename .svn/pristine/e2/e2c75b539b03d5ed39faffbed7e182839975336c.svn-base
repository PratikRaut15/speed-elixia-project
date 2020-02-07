CREATE TABLE `checklist_active_data` (
`at_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`dtid` INT NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL ,
`servicecall_id` INT NOT NULL ,
`customerno` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 20, NOW(), 'vishwanath','checklist');