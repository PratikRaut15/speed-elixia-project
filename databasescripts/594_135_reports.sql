INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'594', '2018-08-08 20:10:00', 'Manasvi Thakur', '135 - All cargo Inactive vehicle report', '0');

CREATE TABLE IF NOT EXISTS `vehrepinterval` (
`vrid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `interval` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;# MySQL returned an empty result set (i.e. zero rows).


ALTER TABLE `vehrepinterval`
 ADD PRIMARY KEY (`vrid`);# MySQL returned an empty result set (i.e. zero rows).


INSERT INTO `reportMaster` (`reportId`, `reportName`, `is_warehouse`, `customerno`, `isdeleted`) 
VALUES (19, 'Inactive Vehicle Alert Report', '0', '135', '0');



UPDATE  dbpatches
SET     patchdate = NOW(),isapplied = 1
WHERE   patchid = 594;