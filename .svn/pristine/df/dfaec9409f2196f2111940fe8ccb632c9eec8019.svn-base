
INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'591', '2018-08-02 19:50:00', 'Arvind Thakur', 'nestle warehouse lat long updated', '0');


update devices set devicelat = '17.63442',devicelong ='78.487437'  where uid = 10428 and customerno = 473;
update devices set devicelat = '17.6317',devicelong ='78.484992'  where uid = 10429 and customerno = 473;
update devices set devicelat = '19.270928',devicelong ='72.968959'  where uid = 10541 and customerno = 473;
update devices set devicelat = '19.270928',devicelong ='72.968959'  where uid = 10546 and customerno = 473;
update devices set devicelat = '30.724526',devicelong ='76.061913'  where uid = 7967 and customerno = 473;


UPDATE  dbpatches
SET     patchdate = NOW(),isapplied = 1
WHERE   patchid = 591;
