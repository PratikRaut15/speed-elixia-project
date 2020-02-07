
-- Insert SQL here.

create table entry(
entryid int(11) primary key auto_increment,
salesid int(11) not null,
distributorid int(11) not null,
areaid int(11) not null,
shopid int(11) not null,
entrydate datetime default null,
remark varchar(250) default null,
customerno int(11) not null,
entrytime datetime default null,
addedby int(11) default null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 9, NOW(), 'Ganesh','Entry master');




