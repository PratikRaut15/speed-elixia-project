
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'474', '2017-03-06 12:00:00', 'Ganesh Papde', 'add customerno column', '0'
);


ALTER TABLE `amc` ADD `customerno` INT(11) NOT NULL AFTER `vehicleid`;


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 474;


