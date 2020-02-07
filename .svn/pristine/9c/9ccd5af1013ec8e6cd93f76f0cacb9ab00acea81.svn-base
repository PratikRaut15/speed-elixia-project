-- Insert SQL here.

alter table user add column panic_email tinyint(1) default 0 after temp_sms;
alter table user add column panic_sms tinyint(1) default 0 after panic_email;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 156, NOW(), 'Akhil VL','Add panic alert columns in user');
