-- Insert SQL here.


ALTER TABLE `route` ADD `devicekey` VARCHAR( 200 ) NOT NULL AFTER `timestamp` ,
ADD `androidid` VARCHAR( 250 ) NOT NULL AFTER `devicekey` ,
ADD `isregister` TINYINT( 1 ) NOT NULL AFTER `androidid` ,
ADD `androidstamp` DATETIME NOT NULL AFTER `isregister` ;






-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 144, NOW(), 'Shrikanth Suryawanshi','Delivery Module Chnages');