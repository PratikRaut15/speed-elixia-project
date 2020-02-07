-- Insert SQL here.


alter table service_call drop column serviceid;
alter table service_call_history drop column serviceid;

create table service_ordered(
soid int(11) primary key auto_increment,
scid int(11) not null,
serviceid int(11) not null,
updatedtime int(11) default null,
updated_by datetime default null,
isdeleted tinyint(1) default 0
);


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 10, NOW(), 'Akhil','service_ordered table');



