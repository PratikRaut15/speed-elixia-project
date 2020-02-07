INSERT INTO `dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('644', '2018-12-28 11:33:00','Arvind Thakur','sqlite dupliacte time deletion log table', '0');

CREATE TABLE duplicateSqliteData (
dsdid INT(11) PRIMARY KEY AUTO_INCREMENT ,
unitid INT(11) NOT NULL,
sqlitedate DATE,
customerno INT(11) NOT NULL ,
created_by INT,
created_on DATETIME,
updated_by INT,
updated_on DATETIME,
isdeleted TINYINT(1) DEFAULT 0
);


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 644;

