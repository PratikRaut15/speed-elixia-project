-- Insert SQL here.

ALTER TABLE  `dealer` ADD  `code` VARCHAR( 225 ) NOT NULL ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 79, NOW(), 'Ajay Tripathi','dealer alter');
