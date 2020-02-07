
-- Insert SQL here.

create table trackie(
trackid int(11) primary key auto_increment,
customerno int(11) not null,
name varchar(105) not null,
phone int(11) not null,
email varchar(105) not null,
address varchar(150) not null,
weekly_off varchar(105) not null,
entrytime datetime not null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 5, NOW(), 'Ganesh','create trackie');


