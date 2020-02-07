-- Insert SQL here.

CREATE TABLE `simdata` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` INT( 11 ) NOT NULL ,
`phoneno` VARCHAR( 50 ) NOT NULL ,
`message` VARCHAR( 50 ) NOT NULL ,
`requesttime` DATE NOT NULL ,
`client` VARCHAR( 60 ) NOT NULL ,
`is_processed` BOOLEAN NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `simdata` CHANGE `requesttime` `requesttime` DATETIME NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 44, NOW(), 'Sanket Sheth','Sim Data');
