INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('604', '2018-09-04 12:12:00', 'Manasvi Thakur', 'Night Drive Travellimng Report', '0');



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicle_number_by_vehicleid`$$
CREATE PROCEDURE `get_vehicle_number_by_vehicleid`(
        IN vehicleidParam INT,
        IN customernoParam INT
    )
BEGIN
    IF(customernoParam = '' OR customernoParam = '0') THEN
		SET customernoParam = NULL;
	END IF;
SELECT v.vehicleNo
    FROM vehicle as v
WHERE v.customerno=customernoParam AND v.vehicleid =vehicleidParam  AND v.isdeleted=0;     
END$$
DELIMITER ;

INSERT INTO `reportMaster` (`reportId`, `reportName`, `is_warehouse`, `customerno`, `isdeleted`) VALUES ('20', 'Night Travelling Report', '0', '0', '0');

CREATE TABLE IF NOT EXISTS `night_drive_details` (
`nightDriveDetId` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `threshold_distance` float NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updatedOn` datetime NOT NULL,
  `isDeleted` TINYINT(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `dailyreport`  
ADD `night_first_lat` FLOAT NOT NULL  AFTER `end_long`,  
ADD `night_first_long` FLOAT NOT NULL  AFTER `night_first_lat`;

UPDATE  dbpatches
SET     patchdate = '2018-09-04 12:12:00',isapplied = 1
WHERE   patchid = 604;


