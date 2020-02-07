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

    IF selVehTypeParam = 'emptyR' THEN

        SELECT DISTINCT(vehicle.vehicleId),vehicle.vehicleNo, tdp.lat,tdp.lng,g.groupname,td.tripid,tyl.isProcessed
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN tripdroppoints tdp ON tdp.tripid = td.tripid AND tdp.created_on = (
            SELECT max(lastTripDropPoint.created_on) FROM tripdroppoints lastTripDropPoint WHERE lastTripDropPoint.vehicleId = vehicle.vehicleId
        )
        INNER JOIN tripYardLog tyl ON tyl.tripid = td.tripid AND tyl.vehicleid = vehicle.vehicleid
        LEFT JOIN `group` g ON g.groupid = vehicle.groupid
        WHERE (vehicle.customerno = customerNoParam)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (td.statusdate > vehicle.checkpoint_timestamp)
        AND (td.customerno = customerNoParam)
        AND td.tripstatusid = 10
        AND td.isdeleted = 0
        AND td.is_tripend = 0
        AND tyl.isProcessed = 0
        Group by td.tripid
        order by td.tripid ASC;


    END IF;

    IF selVehTypeParam = 'tripEnd' THEN

        SELECT DISTINCT(vehicle.vehicleId) , vehicle.vehicleNo,tdp.lat,tdp.lng
        FROM tripdetails T1
        INNER JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
        INNER JOIN tripdroppoints tdp ON tdp.vehicleid = vehicle.vehicleid
        INNER JOIN checkpointmanage CM ON CM.vehicleid = tdp.vehicleid
        WHERE T1.customerno = customerNoParam AND
        tdp.created_on = (SELECT max(lastTripDropPoint.created_on) FROM tripdroppoints lastTripDropPoint
        INNER JOIN vehicle vehicle2 ON vehicle2.vehicleId = lastTripDropPoint.vehicleId WHERE vehicle2.vehicleId = vehicle.vehicleId)
        AND T1.is_tripend = 0
        AND T1.tripstatusid = 10 ;

    END IF;

END$$
DELIMITER ;
CALL get_dashboard_vehicledetails_for_eta('447','2018-08-06','emptyR')
