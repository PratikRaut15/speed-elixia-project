CREATE TABLE `client_type` (
`type_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type_name` TEXT NOT NULL ,
`customerno` INT NOT NULL ,
`userid` INT NOT NULL ,
`timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MYISAM ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 26, NOW(), 'vishu','client type');