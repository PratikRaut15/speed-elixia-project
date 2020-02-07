
-- Insert SQL here.

create table shop(
shopid int(11) primary key auto_increment,
distributorid int(11) not null,
salesid int(11) not null,
areaid int(11) not null,
shopname varchar(105) not null,
phone varchar(105) not null,
phone2 varchar(105) not null,
owner varchar(105) not null,
owner_shop varchar(105) default null,
address varchar(105) default null,
emailid varchar(105) default null,
customerno int(11) not null,
entrytime datetime default null,
addedby int(11) default null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 8, NOW(), 'Ganesh','Shop master');




