INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'670', '2018-02-20 16:30:00', 'Yash Kanakia','Alter Daily Report Table', '0');

ALTER TABLE dailyreport
ADD COLUMN freezeIgnitionOnTime VARCHAR(15) DEFAULT 0;

ALTER TABLE dailyreport
MODIFY COLUMN freezeIgnitionOnTime VARCHAR(15) AFTER weekend_first_odometer;

UPDATE  dbpatches
SET     patchdate = '2018-02-11 12:30:00'
        ,isapplied =1
WHERE   patchid = 670;


