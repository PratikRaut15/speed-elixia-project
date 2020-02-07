
-- Insert SQL here.

create table trip_exception_alerts(
exception_id int(11) primary key auto_increment,
customerno int(11),
userid int(11),
send_sms tinyint(1) default 0,
send_email tinyint(1) default 0,
start_checkpoint_id int(11) default null,
end_checkpoint_id int(11) default null,
report_type varchar(25) default null,
report_type_condition varchar(25) default null,
report_type_val float(10,2) default null,
vehicleid int(11),
cron_flag tinyint(1) default 0,
is_deleted tinyint(1) default 0,
entry_time timestamp,
deleted_by int(11) default null
);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 151, NOW(), 'Akhil VL','New table exception alert');
