create table transmitter(
transmitterid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
transmitterno int NOT NULL,
teamid int NOT NULL,
customerno int(11) NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int(11) NOT NULL,
updated_by int(11) NOT NULL,
isdeleted tinyint(1) DEFAULT '0'
);

create table genset(
gensetid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
gensetno int NOT NULL,
teamid int NOT NULL,
customerno int(11) NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int(11) NOT NULL,
updated_by int(11) NOT NULL,
isdeleted tinyint(1) DEFAULT '0'
);



ALTER TABLE vehicle add no_of_genset int AFTER temp4_max;

ALTER TABLE vehicle add genset1 int AFTER no_of_genset;
ALTER TABLE vehicle add genset2 int AFTER genset1;
ALTER TABLE vehicle add transmitter1 int AFTER genset2;
ALTER TABLE vehicle add transmitter2 int AFTER transmitter1;

ALTER TABLE `transmitter` CHANGE `transmitterno` `transmitterno` VARCHAR(25) NOT NULL;


ALTER TABLE `genset` CHANGE `gensetno` `gensetno` VARCHAR(25) NOT NULL;




INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (356, NOW(), 'Shrikant','Genset and Transmitter Mapping');
