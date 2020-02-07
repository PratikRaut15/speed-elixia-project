-- Insert SQL here.

ALTER TABLE `dealer` ADD `other1` VARCHAR( 255 ) NOT NULL AFTER `userid` ,
ADD `other2` VARCHAR( 255 ) NOT NULL AFTER `other1` ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 127, NOW(), 'Shrikanth Suryawanshi','Dealer Upload File');
