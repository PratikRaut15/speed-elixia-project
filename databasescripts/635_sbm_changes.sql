INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'635', '2018-11-14 17:30:00', 'Manasvi Thakur','SBM changes  ', '0');

ALTER TABLE `vehicle`  ADD `vehicleType` INT(20) NOT NULL DEFAULT '0' ;

CREATE TABLE IF NOT EXISTS `vehicle_type` (
`vehicleTypeId` int(11) NOT NULL,
  `vehicleType` varchar(100) NOT NULL,
  `code` varchar(225) NOT NULL,
  `customerno` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `vehicle_type`
 ADD PRIMARY KEY (`vehicleTypeId`);


ALTER TABLE `vehicle_type`
MODIFY `vehicleTypeId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

INSERT INTO `vehicle_type` (`vehicleType`, `code`,`customerno`,`isdeleted`, `timestamp`, `createdOn`, `updatedOn`) VALUES
    ('Basic','',447,0, '2018-06-08 18:25:14', '2018-06-08 18:25:14', '2018-06-08 18:25:14'),
    ('6 TYRES','',447,0, '2017-10-10 13:18:25', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    ('TEMPO','', 447,0, '2017-10-10 13:06:04', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    ('BULKER','', 447,0, '2017-10-10 13:04:32', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    ('12 TYRE','',447,0, '2017-10-10 13:02:10', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    ('10 TYRE','', 447,0, '2017-10-10 12:57:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

INSERT INTO `uat_speed`.`setting` (`settingid`, `customerno`, `use_location_summary`, `use_vehicle_type`, `createdBy`, `createdOn`, `updatedBy`, `updatedOn`, `isdeleted`) VALUES (NULL, '447', '0', '1', '', '2018-11-14 00:00:00', '', '2018-11-14 00:00:00', '0');


UPDATE  dbpatches
SET     patchdate = '2018-11-14 17:30:00'
        ,isapplied =1
WHERE   patchid = 635;