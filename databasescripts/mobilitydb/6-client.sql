
-- Insert SQL here.


create table client(
clientid int(11) primary key auto_increment,
clientno varchar(55) not null,
client_name varchar(105) not null,
dob date default null,
anniversary date default null,
mobile varchar(15) default null,
phone varchar(15) default null,
email varchar(55) default null,
flatno varchar(25) default null,
building varchar(25) default null,
society varchar(25) default null,
landmark varchar(55) default null,
locationid int(11) default null,
groupid int(11) default 0,
min_billing float(6,2) default null,
customerno int(11) not null,
entrytime datetime not null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 7, NOW(), 'Akhil','Client table');



