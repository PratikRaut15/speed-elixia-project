INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'593', '2018-08-06 20:10:00', 'Manasvi Thakur', 'SBM', '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_dashboard_vehicledetails_for_eta`$$
CREATE PROCEDURE `get_dashboard_vehicledetails_for_eta`(
    IN customerNoParam INT(10),
    IN currentDateParam DATE,
    IN selVehTypeParam VARCHAR(11)
)
BEGIN
    DECLARE  istDateTime INT;
    DECLARE  serverTime INT;
    
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
 ROLLBACK;
     /*
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
       @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
       SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
       SELECT @full_error;
*/
 IF customerNoParam = 0 THEN
  SET customerNoParam = NULL;
END IF;

IF selVehTypeParam = 'emptyR'
THEN
SELECT DISTINCT(vehicle.vehicleId),vehicle.vehicleNo, C.cgeolat, C.cgeolong,TD.lat,TD.lng,CM.checkpointid
FROM tripdetails T1 
INNER JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
INNER JOIN tripdroppoints TD ON TD.vehicleid = vehicle.vehicleid
INNER JOIN checkpointmanage CM ON CM.vehicleid = TD.vehicleid
INNER JOIN checkpoint C ON C.checkpointid = CM.checkpointid
WHERE T1.customerno = customerNoParam 
AND TD.created_on = (SELECT max(TD2.created_on) FROM tripdroppoints TD2 
INNER JOIN vehicle vehicle2 ON vehicle2.vehicleId = TD2.vehicleId WHERE vehicle2.vehicleId = vehicle.vehicleId) 
AND T1.tripstatusid = 10 ;
END IF;

IF selVehTypeParam = 'tripEnd'
THEN
SELECT DISTINCT(vehicle.vehicleId) , vehicle.vehicleNo,TD.lat,TD.lng
FROM tripdetails T1 
INNER JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
INNER JOIN tripdroppoints TD ON TD.vehicleid = vehicle.vehicleid
INNER JOIN checkpointmanage CM ON CM.vehicleid = TD.vehicleid
WHERE T1.customerno = customerNoParam AND
TD.created_on = (SELECT max(TD2.created_on) FROM tripdroppoints TD2 
INNER JOIN vehicle vehicle2 ON vehicle2.vehicleId = TD2.vehicleId WHERE vehicle2.vehicleId = vehicle.vehicleId) 
AND T1.is_tripend = 0 
AND T1.tripstatusid = 10 ;

END IF;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_dashboard_vehicles`$$
CREATE PROCEDURE `get_dashboard_vehicles`(
    IN vehicleTypeParam varchar(30),
    IN customerNoParam INT(10),
    IN currentDateParam datetime
)

BEGIN

    DECLARE  istDateTime INT;
    DECLARE  serverTime INT;
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
 ROLLBACK;
      /*
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
       @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
       SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
       SELECT @full_error;
*/
 IF customerNoParam = 0 THEN
  SET customerNoParam = NULL;
END IF;

 IF vehicleTypeParam = '' THEN
    SET vehicleTypeParam = NULL;
END IF;

IF vehicleTypeParam = 'Avail'
THEN
SELECT DISTINCT(v.vehicleId) ,v.vehicleNo
FROM vehicle v
WHERE v.customerno = customerNoParam
AND v.isdeleted = 0
AND v.vehicleid NOT IN (select tripdetails.vehicleid from tripdetails where is_tripend = 0 AND isdeleted = 0)
AND v.isdeleted =0;
END IF;

IF vehicleTypeParam = 'Intrans'
THEN
SELECT DISTINCT(vehicle.vehicleId) ,vehicle.vehicleNo
FROM tripdetails T1 LEFT JOIN orderDetails O ON O.tripId = T1.tripId
LEFT JOIN deliveryChallan D ON D.orderId = O.orderId
LEFT JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
WHERE T1.customerno= customerNoParam
AND D.orderId  IN (SELECT D2.orderId FROM deliveryChallan D2 WHERE D2.customerno = T1.customerno) ;
END IF;

IF vehicleTypeParam = 'Load'
THEN

SELECT DISTINCT(T1.tripId),vehicle.vehicleNo
FROM tripdetails T1 
INNER JOIN orderDetails O ON O.tripId = T1.tripId
INNER JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
WHERE T1.customerno= customerNoParam
AND O.orderId NOT IN (SELECT D2.orderId FROM deliveryChallan D2 WHERE D2.customerno = T1.customerno) ;

END IF;
END$$


DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW(),isapplied = 1
WHERE   patchid = 593;