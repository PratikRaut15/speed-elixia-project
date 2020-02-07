INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'436', NOW(), 'Ganesh Papde', 'alter user add sms lock columns', '0'
);

ALTER TABLE `user` ADD `sms_count` INT(11) NOT NULL DEFAULT '0' , ADD `sms_lock` TINYINT(1) NOT NULL DEFAULT '0' ;

UPDATE  dbpatches
SET   patchdate = NOW()
  , isapplied =1
WHERE   patchid = 436;
