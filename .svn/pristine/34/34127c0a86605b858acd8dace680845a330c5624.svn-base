INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'415', 'NOW()', 'Shrikant Suryawanshi', 'User Reports', '0'
);


Create Table reportMaster(
reportId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
reportName varchar(150) NOT NULL ,
isdeleted tinyint DEFAULT 0
);

INSERT INTO reportMaster(reportName) values ('Generic Report');
INSERT INTO reportMaster(reportName) values ('Vehicle Movement Report');
INSERT INTO reportMaster(reportName) values ('Stoppage Analysis Report');

Create table userReportMapping(
userReportId int NOT NULL PRIMARy KEY AUTO_INCREMENT ,
reportId int NOT NULL ,
reportTime varchar(10) NOT NULL,
userid int NOT NULL ,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime ,
isdeleted tinyint DEFAULT 0
)


ALTER TABLE `userReportMapping` ADD `isActivated` TINYINT NOT NULL DEFAULT '0' AFTER `reportTime`;


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 415;
