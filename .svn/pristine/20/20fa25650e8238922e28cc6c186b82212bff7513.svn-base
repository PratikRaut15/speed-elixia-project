-- Insert SQL here.

create table service_list(
serviceid int(11) primary key auto_increment,
customerno int(11) not null,
service_name varchar(105) not null,
cost float(10,2) default 0.00,
expected_time int(5) default 0,
entrytime datetime not null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 4, NOW(), 'Akhil VL','Service list table');


