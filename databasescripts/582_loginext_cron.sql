INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'582', '2018-07-24 12:00:00', 'Shrikant Suryawanshi', 'Loginext Api', '0'
);


create table apiTokenLog(
logId int NOT NULL primary key AUTO_INCREMENT,
authToken TEXT NOt NULL,
clientSecretKey TEXT NOT NULL,
validityDate DATE NOT NULL,
customerno int not null,
created_by int Not NUll,
created_on datetime,
updated_by int not null,
updated_on datetime,
isdeleted tinyint default 0
);


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 582;


