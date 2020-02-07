-- Insert SQL here.

create table service_call(
scid int(11) primary key auto_increment,
customerno int(11) not null,
clientid int(11) not null,
trackieid int(11) not null,
serviceid int(11) not null,
service_date datetime not null,
entrytime datetime,
addedby int(11),
updatedtime datetime,
updated_by int(11),
isdeleted tinyint(1) default 0
);

create table service_call_history(
shid int(11) primary key auto_increment,
scid int(11) not null,
customerno int(11) not null,
clientid int(11) not null,
trackieid int(11) not null,
serviceid int(11) not null,
service_date datetime not null,
entrytime datetime,
addedby int(11),
updatedtime datetime,
updated_by int(11),
isdeleted tinyint(1) default 0
);



-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 9, NOW(), 'Akhil','service_call and service_history table');



