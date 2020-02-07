-- Insert SQL here.

create table discount_master(
discountid int(11) primary key auto_increment,
customerno int(11) not null,
discount_code varchar(15) not null,
amount float(7,2) default 0,
percentage float(3,2) default 0,
expiry_date date not null,

entrytime datetime,
addedby int(11) default null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);



-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 13, NOW(), 'Akhil','discount table');



