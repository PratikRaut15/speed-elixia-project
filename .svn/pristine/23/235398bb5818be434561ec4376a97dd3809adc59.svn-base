-- Insert SQL here.
alter table user add column immob_email tinyint(1) default 0 after panic_sms;
alter table user add column immob_sms tinyint(1) default 0 after immob_email;

alter table vehiclewise_alert add column panic_active tinyint(1) default 0;
alter table vehiclewise_alert add column panic_starttime time default '00:00';
alter table vehiclewise_alert add column panic_endtime  time default '23:59';
alter table vehiclewise_alert add column immob_active tinyint(1) default 0;
alter table vehiclewise_alert add column immob_starttime time default '00:00';
alter table vehiclewise_alert add column immob_endtime time default '23:59';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 171, NOW(), 'Akhil','alter user table for immobilizer');
