INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'435', NOW(), 'Ganesh Papde', 'add device lat long columns', '0'
);

ALTER TABLE `tripdetails` ADD `devicelat` FLOAT NOT NULL AFTER `odometer`, ADD `devicelong` FLOAT NOT NULL AFTER `devicelat`;

ALTER TABLE `tripdetail_history` ADD `devicelat` FLOAT NOT NULL AFTER `odometer`, ADD `devicelong` FLOAT NOT NULL AFTER `devicelat`;

UPDATE  dbpatches
SET   patchdate = NOW()
  , isapplied =1
WHERE   patchid = 435;
