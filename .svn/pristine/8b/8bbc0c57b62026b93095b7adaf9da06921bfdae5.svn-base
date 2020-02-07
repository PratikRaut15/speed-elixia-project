INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'489', '2017-04-10 13:19:10', 'Shrikant Suryawanshi', 'Add Column In Vehicle Table For Reset Data', '0'
);


ALTER TABLE `vehicle` ADD `isVehicleReset` TINYINT(1) NOT NULL DEFAULT '0' AFTER `ignition_wirecut`;


CREATE TABLE `geofence_archive` (
  `geofencearchiveid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `geofenceid` int(11) NOT NULL,
  `fenceid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `geolat` float NOT NULL,
  `geolong` float NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_on` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DELIMITER $$
DROP TRIGGER IF EXISTS before_geofence_delete$$
CREATE TRIGGER before_geofence_delete BEFORE DELETE ON geofence
FOR EACH ROW BEGIN
	SET @serverTime = now();
	SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
	INSERT INTO geofence_archive(
				geofenceid,
				fenceid,
				customerno,
				geolat,
				geolong,
				isdeleted,
				userid,
				created_on
		) VALUES (
				OLD.geofenceid,
				OLD.fenceid,
				OLD.customerno,
				OLD.geolat,
				OLD.geolong,
				OLD.isdeleted,
				OLD.userid,
				@istDateTime
		);
END$$
DELIMITER ;



ALTER TABLE `vehicle` CHANGE `isVehicleReset` `isVehicleResetCmdSent` TINYINT( 1 ) NOT NULL DEFAULT '0'


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 489;
