-- Insert SQL here.

ALTER TABLE  `user` ADD  `heirarchy_id` INT( 11 ) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 95, NOW(), 'AJAY TRIPATHI','heirarchy in user table');
