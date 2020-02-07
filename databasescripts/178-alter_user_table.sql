-- Insert SQL here.
alter table user add column door_sms tinyint(1) default 0 after immob_sms;
alter table user add column door_email tinyint(1) default 0 after door_sms;

alter table vehiclewise_alert add column door_active tinyint(1) default 0;
alter table vehiclewise_alert add column door_starttime time default '00:00:00';
alter table vehiclewise_alert add column door_endtime time default '23:59:00';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 178, NOW(), 'Akhil','alter user table');
