INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'544', '2018-03-15 18:10:00', 'Manasvi Thakur', 'Speed : To add  Specific Roles for customer #64 (Mahindra Finance) under adduser and edituser pages in Masters/ Users', '0'
);

UPDATE role
SET moduleid='1'
WHERE customerno='64';


UPDATE  dbpatches
SET isapplied =1
WHERE   patchid = 544;
