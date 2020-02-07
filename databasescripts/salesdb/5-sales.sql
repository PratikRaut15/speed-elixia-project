
-- Insert SQL here.

create table sales(
salesid int(11) primary key auto_increment,
asmid int(11) not null,
srcode varchar(105) not null,
srname varchar(105) not null,
phone varchar(15) not null,
customerno int(11) not null,
entrytime datetime not null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 5, NOW(), 'Ganesh','Sales master');




