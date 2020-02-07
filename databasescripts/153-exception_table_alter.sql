
-- Insert SQL here.

alter table `trip_exception_alerts`  change cron_flag trip_endtime_flag datetime default null;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 153, NOW(), 'Akhil VL','trip exception alteration');
