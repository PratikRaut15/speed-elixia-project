
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'479', '2017-03-09 16:23:00', 'Ganesh Papde', 'cron fuel alert table', '0'
);

create table fuelcron_alertlog(
  `fid` int(11) primary key auto_increment,
  `userid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `countcheck` int(11) NOT NULL,
  `alerttype` tinyint(3) NOT NULL,
  `old_fuel_balance` decimal(6,2) NOT NULL,
  `lastupdated` datetime DEFAULT NULL,
  `threshold_conflict_status` tinyint(2) NOT NULL DEFAULT '0',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 479;
