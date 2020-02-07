-- Insert SQL here.

CREATE TABLE `customfield` (
`cfid` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 50 ) NOT NULL ,
`customname` VARCHAR( 50 ) NOT NULL ,
`usecustom` BOOL NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`date_modified` DATETIME NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `customfield` (
`cfid` ,
`name` ,
`customname` ,
`usecustom` ,
`customerno` ,
`date_modified`
)
VALUES (
NULL , 'Digital Connection', 'Genset', '0', '1', '2014-02-14 11:46:59'
);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 50, NOW(), 'Sanket Sheth','Custom Field');
