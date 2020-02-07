ALTER TABLE `secondary_order` ADD `srid` INT(11) NOT NULL AFTER `entrydate`;

create table syncdata(
syncid int(11) primary key auto_increment,
operation_type tinyint(2) not null,
table_type tinyint(2) not null,
rowid int(11) not null,
`timestamp` datetime default null
);

create table userpull_sucess(
 usid int(11) primary key auto_increment,
 userid int(11) not null,
 lastsync_datetime datetime default null
);

ALTER TABLE `syncdata` ADD `srid` INT(11) NOT NULL AFTER `rowid`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 10, NOW(), 'Ganesh','Secondary order');