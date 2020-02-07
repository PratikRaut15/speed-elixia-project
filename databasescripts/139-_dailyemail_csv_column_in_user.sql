-- Insert SQL here.

alter table user add column dailyemail_csv tinyint(1) default 0;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 139, NOW(), 'Akhil VL','New column, dailyemail_csv in user table');


