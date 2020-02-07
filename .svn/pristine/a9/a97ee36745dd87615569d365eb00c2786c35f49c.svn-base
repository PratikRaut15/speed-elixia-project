
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'467', '2017-02-21 15:20:40', 'Ganesh', 'Alter User Menumapping', '0'
);


ALTER TABLE `usermenu_mapping` CHANGE `view_permission` `add_permission` TINYINT(1) NOT NULL DEFAULT '0';

UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 467;

