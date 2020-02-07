-- Insert SQL here.

create table vehiclewise_alert(

veh_alert_id int(11) auto_increment primary key,
customerno int(11) default null,
userid int(11) default null,
vehicleid int(11) default null,
updated_date timestamp,
isdeleted tinyint(1) default 0,
deleted_by int(11) default null,

temp_active tinyint(1) default 0,
temp_starttime time default "00:00:00",
temp_endtime time default "23:59:00",

ignition_active tinyint(1) default 0,
ignition_starttime time default "00:00:00",
ignition_endtime time default "23:59:00",

speed_active tinyint(1) default 0,
speed_starttime time default "00:00:00",
speed_endtime time default "23:59:00",

ac_active tinyint(1) default 0,
ac_starttime time default "00:00:00",
ac_endtime time default "23:59:00",

powerc_active tinyint(1) default 0,
powerc_starttime time default "00:00:00",
powerc_endtime time default "23:59:00",

tamper_active tinyint(1) default 0,
tamper_starttime time default "00:00:00",
tamper_endtime time default "23:59:00",

harsh_break_active tinyint(1) default 0, 
harsh_break_starttime time default "00:00:00",
harsh_break_endtime time default "23:59:00",

high_acce_active tinyint(1) default 0, 
high_acce_starttime time default "00:00:00",
high_acce_endtime time default "23:59:00",

towing_active tinyint(1) default 0, 
towing_starttime time default "00:00:00",
towing_endtime time default "23:59:00"

);

ALTER TABLE `vehiclewise_alert` ADD INDEX ( `vehicleid` ) ;
ALTER TABLE `vehiclewise_alert` ADD INDEX ( `userid` ) ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 162, NOW(), 'Akhil VL','Vehiclewise Alert Table');
