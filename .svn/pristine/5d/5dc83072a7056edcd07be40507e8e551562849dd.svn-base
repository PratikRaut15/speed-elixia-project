
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'450', '2017-01-20 12:18:52', 'Ganesh Papde', 'Trigon Changes or cron sms consumed report', '0'
);


INSERT INTO `speed`.`maintenance_status` (`id`, `name`) VALUES (NULL, 'cancelled');
ALTER TABLE `maintenance` ADD `is_cancelled` TINYINT(2) NOT NULL DEFAULT '0' ;
ALTER TABLE `maintenance_history` ADD `is_cancelled` TINYINT(2) NOT NULL DEFAULT '0' ;
INSERT INTO `speed`.`reportMaster` (`reportId`, `reportName`, `isdeleted`) VALUES (NULL, 'SMS Consumed Details', '0');


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 450;
