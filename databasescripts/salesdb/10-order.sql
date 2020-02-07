
-- Insert SQL here.

create table orders(
orderid int(11) primary key auto_increment,
salesid int(11) not null,
distributorid int(11) not null,
areaid int(11) not null,
shopid int(11) not null,
catid int(11) not null,
styleid int(11) not null,
quantity int(11) not null,
orderdate datetime default null,
customerno int(11) not null,
entrytime datetime default null,
addedby int(11) default null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 10, NOW(), 'Ganesh','Order');




