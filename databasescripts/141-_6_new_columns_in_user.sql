-- Insert SQL here.


alter table user 
add column harsh_break_sms tinyint(1) default 0,
add column harsh_break_mail tinyint(1) default 0,
add column high_acce_sms tinyint(1) default 0,
add column high_acce_mail tinyint(1) default 0,
add column sharp_turn_sms tinyint(1) default 0,
add column sharp_turn_mail tinyint(1) default 0,
add column towing_sms tinyint(1) default 0,
add column towing_mail tinyint(1) default 0
;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 141, NOW(), 'Akhil VL','6 New column in user table');


