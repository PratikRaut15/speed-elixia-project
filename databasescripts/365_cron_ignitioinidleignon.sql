-- Insert SQL here.

alter table dailyreport add idleignitiontime int NOT NULL after runningtime;

alter table ignitionalert add prev_odometer_reading int NOT NULL after ignontime;
alter table ignitionalert add prev_odometer_time DATETIME NOT NULL after prev_odometer_reading;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 365, NOW(), 'Shrikant Suryawanshi','Cron Ignition And Dailyreport Changes - IdleIgnitionOnTime');

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 365;