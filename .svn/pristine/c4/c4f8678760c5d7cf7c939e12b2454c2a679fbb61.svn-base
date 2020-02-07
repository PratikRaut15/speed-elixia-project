-- Insert SQL here.


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

first_odometer float(12,2) default 0.00,
last_odometer float(12,2) default 0.00,
max_odometer float(12,2) default 0.00,
totaldistance float(11,2) default 0.00,
overspeed int(11)  default 0,

fenceconflict int(11)  default 0,
acusage varchar(15)  default '0',
runningtime varchar(15)  default '0',
first_lat varchar(55)  default '0',
first_long varchar(55)  default '0',
end_lat varchar(55)  default '0',
end_long varchar(55)  default '0'
);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 165, NOW(), 'Akhil VL','dailyreort Table');
