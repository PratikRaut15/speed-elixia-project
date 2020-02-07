-- Insert SQL here.

alter table geotest add column checkpointid int(11) default null;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 147, NOW(), 'Akhil VL','new column in geotest table');
