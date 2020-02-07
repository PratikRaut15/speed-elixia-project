-- Insert SQL here.

ALTER TABLE  `ignitionalert` ADD  `ignchgtime` DATETIME NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 47, NOW(), 'Ajay Tripathi','Ignition change column');
