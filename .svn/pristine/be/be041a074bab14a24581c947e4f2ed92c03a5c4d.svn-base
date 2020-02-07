-- Insert SQL here.
create table trip_alert(
trip_alert_id int(11) primary key auto_increment,
customerno int(11),
userid int(11),
vehicleid int(11),
start_date date,
start_time time,
start_checkpoint_id int(11),
end_checkpoint_id int(11),
entry_time datetime,
isdeleted tinyint(1) default 0
);



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 167, NOW(), 'Akhil','trip table');
