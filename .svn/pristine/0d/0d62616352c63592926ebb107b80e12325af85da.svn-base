INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'681', '2018-03-08 15:30:00', 'Yash Kanakia','Vehicle Audit Trail', '0');


UPDATE vehicle
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;

UPDATE eventalerts
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;

UPDATE ignitionalert
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;

UPDATE batch
SET
createdBy =298,
updatedBy =298
WHERE customerno = 64;


UPDATE checkpointmanage
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;


UPDATE fenceman
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;




UPDATE  dbpatches
SET     patchdate = '2018-03-08 15:30:00'
        ,isapplied =1
WHERE   patchid = 681;