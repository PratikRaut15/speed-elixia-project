INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('641', '2018-12-04 19:30:00', 'Yash Kanakia','Route Dashboard Changes', '0');

ALTER TABLE setting
ADD COLUMN use_checkpoint_settings tinyint default 0;

INSERT INTO setting(customerno,use_checkpoint_settings)
VALUES('682','0');

UPDATE  dbpatches
SET     patchdate = '2018-12-04 19:30:00'
        ,isapplied =1
WHERE   patchid = 641;