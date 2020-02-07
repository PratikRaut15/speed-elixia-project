
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'451', '2017-01-21 18:00:52', 'Ganesh Papde', 'maintenance task and parts changes', '0'
);


ALTER TABLE `parts` ADD `unitamount` DECIMAL(11,2) NOT NULL DEFAULT '0' AFTER `part_name`, ADD `unitdiscount` DECIMAL(10,2) NOT NULL DEFAULT '0'  AFTER `unitamount`;
ALTER TABLE `task` ADD `unitamount` DECIMAL(11,2) NOT NULL DEFAULT '0' AFTER `task_name`, ADD `unitdiscount` DECIMAL(10,2) NOT NULL  DEFAULT '0' AFTER `unitamount`;

UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 451;
