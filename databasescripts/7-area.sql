
-- Insert SQL here.

create table area(
areaid int(11) primary key auto_increment,
distributorid int(11) not null,
areaname varchar(105) not null,
customerno int(11) not null,
entrytime datetime default null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 7, NOW(), 'Ganesh','Area master');




