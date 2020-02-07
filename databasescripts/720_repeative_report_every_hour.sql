INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`)
VALUES ('720', '2019-07-11 17:26:00', 'Arvind Thakur','report after every predefined interval', '0');

ALTER TABLE `userReportMapping`
ADD COLUMN iterativeReportHour TINYINT DEFAULT 0 AFTER reportTime;

UPDATE  dbpatches
SET     updatedOn = DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE)
        ,isapplied = 1
WHERE   patchid = 720;