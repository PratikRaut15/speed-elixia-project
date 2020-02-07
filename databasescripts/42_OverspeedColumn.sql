-- Insert SQL here.
ALTER TABLE  `vehicle` ADD  `overspeed_limit` SMALLINT( 3 ) NOT NULL DEFAULT  '80';


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 42, NOW(), 'Ajay Tripathi','Overspeed');
