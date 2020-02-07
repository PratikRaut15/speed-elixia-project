
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'453', '2017-01-28 16:09:00', 'Arvind Thakur', 'Checkpoint status on RTD', '0'
);


alter table `vehicle`
add column `chkpoint_status` TINYINT(2) default 0 after `checkpointId`;

ALTER TABLE `vehicle` ADD `checkpoint_timestamp` DATETIME NULL DEFAULT NULL AFTER `chkpoint_status`;


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 453;





