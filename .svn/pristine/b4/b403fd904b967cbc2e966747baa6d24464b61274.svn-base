-- Insert SQL here.

CREATE TABLE `servicecall` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`uid` INT( 11 ) NOT NULL ,
`simcardid` INT( 11 ) NOT NULL ,
`vehicleid` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `servicecall` ADD `thid` INT( 11 ) NOT NULL ;

ALTER TABLE `servicecall` ADD `teamid` INT( 11 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 140, NOW(), 'Sanket Sheth','Service Call');
