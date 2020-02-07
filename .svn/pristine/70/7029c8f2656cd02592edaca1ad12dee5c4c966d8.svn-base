
-- Insert SQL here.
create table style(
styleid int(11) primary key auto_increment,
categoryid int(11) not null,
styleno varchar(105) not null,
mrp FLOAT(6,2) default null,
distprice FLOAT(6,2) default null,
retailprice FLOAT(6,2) default null,
customerno int(11) not null,
entrytime datetime not null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 2, NOW(), 'Ganesh','Style master create');




