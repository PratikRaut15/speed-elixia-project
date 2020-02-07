INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'565', '2018-05-29 18:00:00', 'Arvind Thakur', 'Temperature Report Interval Configurable', '0'
);

DROP TABLE IF EXISTS `tempreportinterval`;
CREATE TABLE tempreportinterval (
  `trid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `interval` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

UPDATE dbpatches SET isapplied=1 WHERE patchid = 565;