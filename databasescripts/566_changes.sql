INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'566', '2018-06-20 14:27:00', 'Arvind Thakur', 'temperature alert sms email specific min max', '0'
);



DROP TABLE IF EXISTS `tempalertsmsrange`;
CREATE TABLE tempalertsmsrange (
  `tasrid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `temp1_min` decimal(5,2) NOT NULL,
  `temp1_max` decimal(5,2) NOT NULL,
  `temp1_mute` tinyint(1) NOT NULL,
  `temp2_min` decimal(5,2) NOT NULL,
  `temp2_max` decimal(5,2) NOT NULL,
  `temp2_mute` tinyint(1) NOT NULL,
  `temp3_min` decimal(5,2) NOT NULL,
  `temp3_max` decimal(5,2) NOT NULL,
  `temp3_mute` tinyint(1) NOT NULL,
  `temp4_min` decimal(5,2) NOT NULL,
  `temp4_max` decimal(5,2) NOT NULL,
  `temp4_mute` tinyint(1) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `user`
ADD COLUMN `isTempInrangeAlertRequired` TINYINT(1) DEFAULT 1;

ALTER TABLE `user`
ADD COLUMN `isAdvTempConfRange` TINYINT(1) DEFAULT 0;


UPDATE dbpatches SET isapplied=1 WHERE patchid = 566;