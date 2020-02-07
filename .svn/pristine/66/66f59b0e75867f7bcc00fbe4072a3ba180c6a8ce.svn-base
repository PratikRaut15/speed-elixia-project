-- Insert SQL here.
ALTER TABLE  `vehicle` ADD  `other_upload1` VARCHAR( 250 ) NOT NULL ,
ADD  `other_upload2` VARCHAR( 250 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 84, NOW(), 'Ajay Tripathi','other uploads field');
