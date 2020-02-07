-- Insert SQL here.

create table client_address(
client_add_id int(11) primary key auto_increment,
clientid int(11),
flatno varchar(25),
building varchar(25),
society varchar(25),
landmark varchar(55),
locationid int(11),
cityid int(11),
entrytime datetime not null,
added_by int(11),
updatedtime  datetime default null,
updated_by int(11),
isdeleted tinyint(1) default 0
);


insert into client_address select null, clientid, flatno, building, society, landmark, locationid, cityid, now(), 1, null, null, 0 from client;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 16, NOW(), 'akhil','client address table');



