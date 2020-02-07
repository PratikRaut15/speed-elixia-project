
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'455', '2017-02-03 16:09:00', 'Ganesh Papde', 'Maintenance and maintenance history alter', '0'
);


ALTER TABLE `maintenance` ADD `behalfid` INT(11) NOT NULL DEFAULT '0' AFTER `userid`;
ALTER TABLE `maintenance_history` ADD `behalfid` INT(11) NOT NULL DEFAULT '0' AFTER `userid`;


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 455;





