
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'452', '2017-01-24 15:00:00', 'Shrikant Suryawanshi', 'Map Ledger Id With Invoice', '0'
);


ALTER TABLE `invoice` ADD `ledgerid` INT NOT NULL AFTER `customerno`;


UPDATE invoice SET ledgerid = (SELECT  CAST(REPLACE(REPLACE(SUBSTRING(invoiceno,3), customerno, ''), invoiceid, '') AS UNSIGNED)  as ledgerid
 ) WHERE inv_date >= '2016-04-30';



UPDATE  dbpatches
SET     patchdate = '2017-01-24 15:00:00'
    , isapplied =1
WHERE   patchid = 452;
