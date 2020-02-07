INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'686', '2018-03-13 12:50:00', 'Yash Kanakia','Freeze Log Changes', '0');

ALTER TABLE freezelog
ADD COLUMN isAlertSent tinyINT DEFAULT 0;



UPDATE  dbpatches
SET     patchdate = '2018-03-13 12:50:00'
        ,isapplied =1
WHERE   patchid = 686;



