-- Insert SQL here.

drop table dailyreport;

create table dailyreport(
dailyreport_id int(11) primary key auto_increment,
customerno int(11) default 0,
vehicleid int(11) default 0,
uid int(11) default 0,

harsh_break int(11) default 0,
sudden_acc int(11) default 0,
towing int(11) default 0,
flag_harsh_break tinyint(1) default 0,
flag_sudden_acc tinyint(1) default 0,
flag_towing tinyint(1) default 0,

first_odometer varchar(15) default 0,
last_odometer varchar(15) default 0,
max_odometer varchar(15) default 0,
average_distance float(8,2) default 0.00,
total_tracking_days int(11) default 42,
overspeed int(11)  default 0,
topspeed int(11) default 0,
topspeed_lat float default 0,
topspeed_long float default 0,

fenceconflict int(11)  default 0,
acusage varchar(15)  default 0,
runningtime varchar(15)  default 0,
first_lat float  default 0,
first_long float  default 0,
end_lat float  default 0,
end_long float  default 0
);


insert into dailyreport(dailyreport_id, customerno, vehicleid, uid, first_odometer, first_lat, 	first_long, total_tracking_days)
SELECT '', a.customerno, a.vehicleid, a.uid, a.odometer, b.devicelat, b.devicelong, 1 as total_tracking_days
FROM vehicle as a 
left join devices as b on a.uid=b.uid 
where a.isdeleted=0 and a.uid!=0;

DELETE FROM dailyreport WHERE customerno = 1;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 184, NOW(), 'Akhil VL','new dailyreport Table');
