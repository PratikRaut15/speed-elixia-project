-- Insert SQL here.

alter table trip_alert add column driving_distance float(10,2) default null;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 202, NOW(), 'Akhil VL','new column in trip alert');
