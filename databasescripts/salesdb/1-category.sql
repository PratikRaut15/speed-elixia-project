
-- Insert SQL here.
create database secondary_sales;

CREATE TABLE `dbpatches` (
 `patchid` int(11) NOT NULL,
 `patchdate` datetime NOT NULL,
 `appliedby` varchar(20) NOT NULL,
 `patchdesc` varchar(255) NOT NULL,
 PRIMARY KEY (`patchid`)
);

create table category(
categoryid int(11) primary key auto_increment,
categoryname varchar(105) not null,
customerno int(11) not null,
entrytime datetime not null,
addedby int(11) not null,
updatedtime datetime default null,
updated_by int(11) default null,
isdeleted tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 1, NOW(), 'Ganesh','create Category');



