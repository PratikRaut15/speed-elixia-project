INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'618', '2018-09-26 15:30:00', 'Sanjeet Shukla', 'Updated customerNo = 0 in orderType Table to display orderType for All customers', '0'
);


update orderType set customerNo = 0;

UPDATE  dbpatches
SET     updatedOn = NOW(),isapplied = 1
WHERE   patchid = 618;
