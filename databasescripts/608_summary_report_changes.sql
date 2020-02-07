INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES ('608', '2018-09-07 18:00:00', 'Arvind Thakur', 'Customized Summary Report', '0');

ALTER TABLE `dailyreport`
ADD COLUMN `driverid` INT(11) DEFAULT NULL AFTER `uid`;


UPDATE  `dailyreport` d  
JOIN    `vehicle` v ON v.vehicleid = d.vehicleid
SET     d.driverid = v.driverid;


CREATE TABLE IF NOT EXISTS `setting` (
  `settingid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `use_location_summary` TINYINT(2) NOT NULL DEFAULT 0,
  `createdBy` INT NOT NULL,
  `createdOn` DATETIME,
  `updatedBy` INT NOT NULL,
  `updatedOn` DATETIME,
  `isdeleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`settingid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `setting` (`customerno`,`use_location_summary`) VALUES (531,1);


UPDATE dbpatches SET isapplied=1 WHERE patchid = 608;