-- Insert SQL here.

ALTER TABLE  `group` ADD  `cityid` INT( 11 ) NOT NULL AFTER  `groupname` ,
ADD  `code` VARCHAR( 225 ) NOT NULL AFTER  `cityid` ,
ADD  `address` TEXT NOT NULL AFTER  `code`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 63, NOW(), 'Ajay Tripathi','Alter Group Table');
