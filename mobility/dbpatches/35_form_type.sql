CREATE TABLE `form_type` (
`form_type_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`form_type_name` VARCHAR( 255 ) NOT NULL ,
`customerno` INT NOT NULL ,
`userid` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 35, NOW(), 'vishu','form type');