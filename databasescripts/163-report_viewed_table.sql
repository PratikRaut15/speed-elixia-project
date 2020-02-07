-- Insert SQL here.

create table reports_viewed(
view_id int(11) primary key auto_increment,
customerno int(11),
userid int(11),
vehicleid int(11) default null,
report_date date,
report_name varchar(20),
file_type varchar(10),
viewed_date timestamp default now()
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 163, NOW(), 'Akhil VL','Report viewed Table');
