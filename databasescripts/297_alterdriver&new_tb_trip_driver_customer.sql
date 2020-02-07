--
-- Table structure for table `trip_driver`
--

CREATE TABLE IF NOT EXISTS `trip_driver` (
`trip_driver_id` int(11) NOT NULL,
  `driverid` int(11) NOT NULL,
  `trip_start` datetime NOT NULL,
  `start_odometer` bigint(20) NOT NULL,
  `start_lat` float NOT NULL,
  `start_long` float NOT NULL,
  `trip_end` datetime NOT NULL,
  `end_odometer` bigint(20) NOT NULL,
  `end_lat` float NOT NULL,
  `end_long` float NOT NULL,
  `createdby` int(11) NOT NULL,
  `updatedby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);


ALTER TABLE `trip_driver`
 ADD PRIMARY KEY (`trip_driver_id`);

--
-- AUTO_INCREMENT for table `trip_driver`
--
ALTER TABLE `trip_driver`
MODIFY `trip_driver_id` int(11) NOT NULL;


--Driver table new fields----------
ALTER TABLE `driver`  ADD `username` VARCHAR(20) NOT NULL ,  ADD `password` VARCHAR(150) NOT NULL ,  ADD `userkey` INT(11) NOT NULL, ADD `trip_email` VARCHAR(50) NOT NULL ;



--Pocedure----

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_start_trip`$$
CREATE PROCEDURE `insert_start_trip`( 
	IN 	driverid INT(11)
	, IN trip_start DATETIME
	, IN start_odometer BIGINT(20)
	, IN start_lat FLOAT
	, IN start_long FLOAT
	, IN createdby INT(11)
	, IN  createdon DATETIME
    , OUT currenttripid INT
)
BEGIN
	INSERT INTO trip_driver 
				(
					driverid 
					, trip_start 
					, start_odometer 
					, start_lat 
					, start_long 
					, createdby 
					, createdon 
					
				) 
	VALUES 		(
					driverid 
					, trip_start 
					, start_odometer 
					, start_lat 
					, start_long 
					, createdby 
					, createdon 
				);

        
	SET currenttripid = LAST_INSERT_ID();
END$$
DELIMITER ;


---update procedure----

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_end_trip`$$
CREATE  PROCEDURE `update_end_trip`( 
	IN 	driver_id INT(11)
    , IN tripid INT(11)
	, IN trip_end DATETIME
	, IN end_odometer BIGINT(20)
	, IN end_lat FLOAT
	, IN end_long FLOAT
	, IN updatedby INT(11)
	, IN  updatedon DATETIME
)
BEGIN
	UPDATE trip_driver
	SET 
		 trip_end= trip_end
					, end_odometer= end_odometer
					, end_lat= end_lat 
					, end_long= end_long
					, updatedby = updatedby 
					, updatedon = updatedon
                    
	WHERE driverid = driver_id AND trip_driver_id = tripid ;
END$$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (297, NOW(), 'Sahil Gandhi','alter driver & new trip_driver');
