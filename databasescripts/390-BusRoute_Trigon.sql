INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'390', '2016-05-26 20:44:01', 'Shrikant Suryawanshi', 'BusRoute module for trigon transit -- 118', '0'
);


Create Table student_master(
studentId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
studentName varchar(150) NOT NULL,
enrollmentNo varchar(50) NOT NULL,
centerId int NOT NULL,
board varchar(20) NOT NULL,
grade varchar(20) NOT NULL,
division varchar(10) NOT NULL,
address text NOT NULL,
building varchar(100) NOT NULL,
street varchar(100) NOT NULL,
landmark varchar(100) NOT NULL,
area varchar(50) NOT NULL,
station varchar(50) NOT NULL,
city varchar(50) NOT NULL,
pincode int NOT NULL,
isBusStudent tinyint DEFAULT '0',
lat decimal(10,6) NOT NULL,
lng decimal(10,6) NOT NULL,
accuracy int NOT NULL,
customerno int NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint DEFAULT '0'
);



Create Table busStop(
busStopId int PRIMARY KEY AUTO_INCREMENT,
schoolId int NOT NULL,
lat decimal(10,6) NOT NULL,
lng decimal(10,6) NOT NULL,
distanceFromSchool decimal(5,2) NOT NULL,
address text NOT NULL,
customerno int NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint DEFAULT '0'
);

Create Table busStopStudentMapping(
busStopStudentMappingId int PRIMARY KEY AUTO_INCREMENT,
busStopId int NOT NULL,
studentId int NOT NULL,
schoolId int NOT NULL,
distanceFromStop decimal(5,2) NOT NULL,
customerno int NOT NULL,
created_on datetime NOT NULL,
updated_on datetime NOT NULL,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint DEFAULT '0'
);

ALTER TABLE `busStop` ADD `zoneId` INT NOT NULL AFTER `address`;



CREATE TABLE `bus_route_sequence` (
  `sequence_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `busStopId` int(11) NOT NULL,
  `sequence` int(5) NOT NULL,
  `time_taken` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `bus_route_sequence`
  ADD PRIMARY KEY (`sequence_id`);


ALTER TABLE `bus_route_sequence`
  MODIFY `sequence_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `student_master` ADD `distance` DECIMAL(5,2) NOT NULL AFTER `accuracy`;
ALTER TABLE `busStop` ADD `isAlloted` INT NOT NULL AFTER `zoneId`;
ALTER TABLE `bus_route_sequence` ADD `routeId` INT NOT NULL AFTER `sequence`;

create Table routeSeed(
id int NOT NULL,
routeId int NOT NULL
);




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_bus_stops`$$
CREATE PROCEDURE `get_bus_stops`(
		 IN busStopIdParam INT
         ,IN zoneIdParam INT
         ,IN custno INT
)
BEGIN

    IF(busStopIdParam = 0) THEN
	SET busStopIdParam = NULL;
    END IF;
    IF(zoneIdParam = 0) THEN
	SET zoneIdParam = NULL;
    END IF;
	IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;


	SELECT
		busStopId,
		schoolId,
		lat,
		lng,
		distanceFromSchool,
		address,
		zoneid as zoneId,
		isAlloted,
		customerno,
		created_on,
		updated_on,
		created_by,
		updated_by,
		isdeleted
	FROM busStop
	WHERE (busStop.customerno = custno OR custno IS NULL)
	AND (busStop.busStopId = busStopIdParam OR busStopIdParam IS NULL )
	AND (busStop.zoneid = zoneIdParam OR zoneIdParam IS NULL )
	AND busStop.isdeleted = 0;


END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_bus_routes`$$
CREATE PROCEDURE `get_bus_routes`(
		 IN busStopIdParam INT
		 ,IN vehicleIdParam INT
)
BEGIN


    IF(busStopIdParam = 0) THEN
	SET busStopIdParam = NULL;
    END IF;
	IF(vehicleIdParam = 0) THEN
	SET vehicleIdParam = NULL;
    END IF;



	SELECT
		sequence_id,
		vehicle_id,
		vehicle.vehicleno,
		brs.busStopId,
		busStop.address,
		sequence,
		routeId,
		time_taken,
		update_time,
		brs.updated_by

	FROM bus_route_sequence as brs
	INNER JOIN vehicle on vehicle.vehicleid = brs.vehicle_id
	INNER JOIN busStop on busStop.busStopId = brs.busStopId
	WHERE (brs.busStopId = busStopIdParam OR busStopIdParam IS NULL )

	AND (brs.vehicle_id = vehicleIdParam OR vehicleIdParam IS NULL )
	Order By Vehicleid, sequence ASC;


END$$
DELIMITER ;



-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches
SET 	patchdate = NOW()
		, isapplied =1
WHERE 	patchid = 390;


DELIMITER $$
DROP PROCEDURE `get_bus_routes`$$
CREATE DEFINER=`UserSpeed`@`localhost` PROCEDURE `get_bus_routes`(
         IN busStopIdParam INT
         ,IN vehicleIdParam INT
        ,IN routeIdParam INT
)
BEGIN


    IF(busStopIdParam = 0) THEN
    SET busStopIdParam = NULL;
    END IF;
    IF(vehicleIdParam = 0) THEN
    SET vehicleIdParam = NULL;
    END IF;
    IF(routeIdParam = 0) THEN
    SET routeIdParam = NULL;
    END IF;



    SELECT
        sequence_id,
        vehicle_id,
        vehicle.vehicleno,
        brs.busStopId,
        busStop.address,
        busStop.busStopName,
        sequence,
        trigonRoute.`name`,
        brs.routeId,
        time_taken,
        update_time,
        brs.updated_by

    FROM bus_route_sequence as brs
    INNER JOIN vehicle on vehicle.vehicleid = brs.vehicle_id
    INNER JOIN busStop on busStop.busStopId = brs.busStopId
    INNER JOIN speed.trigonRoute on trigonRoute.trouteid = brs.routeId
    WHERE (brs.busStopId = busStopIdParam OR busStopIdParam IS NULL )
    AND (brs.vehicle_id = vehicleIdParam OR vehicleIdParam IS NULL )
    Order By brs.routeId, Vehicleid, sequence ASC;


END$$
DELIMITER ;
