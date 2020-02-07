-- Insert SQL here.


alter table dailyreport change column `first_odometer` `first_odometer` bigint(15) default 0;
alter table dailyreport change column `last_odometer` `last_odometer` bigint(15) default 0;
alter table dailyreport change column `max_odometer` `max_odometer` bigint(15) default 0;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 257, NOW(), 'Akhil','alter dailyreport odometer type');
