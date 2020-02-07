INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('602', '2018-08-24 17:45:00', 'Manasvi Thakur', 'Update nomenclature for PerkinElmer', '0');

INSERT INTO `nomens` (`name`, `customerno`, `isdeleted`) 
VALUES ('MDx Freezer-002', '421', '0');

INSERT INTO `nomens` (`name`, `customerno`, `isdeleted`) 
VALUES ('Refrigerator-007', '421', '0');

UPDATE unit
SET    n1=46
       ,n2 = 47
       ,n3 = 28
       ,n4 = 34
WHERE  uid = 8134;

update  unit
set     n3 = 19
where   uid = 3405;

update  unit
set     n3 = 35
where   uid = 4489;


UPDATE  dbpatches
SET     patchdate = NOW(),isapplied = 1
WHERE   patchid = 602;