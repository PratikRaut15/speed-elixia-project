

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'461', '2017-02-13 15:20:40', 'Arvind Thakur', 'new type incomplete in bucket_status table', '0'
);


insert into bucket_status(`id`,`type`) values(5,'Incomplete');

UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 461;