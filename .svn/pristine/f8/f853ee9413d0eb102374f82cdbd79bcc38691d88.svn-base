-- Insert SQL here.
ALTER TABLE `comqueue` CHANGE `lat` `devlat` FLOAT NOT NULL, CHANGE `long` `devlong` FLOAT NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 36, NOW(), 'Ajay Tripathi','change column name in Comqueue');
