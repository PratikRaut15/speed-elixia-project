INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'595', '2018-08-09 20:10:00', 'Shrikant Suryawanshi', 'SBM - Trip Changes', '0');


create Table tripYardLog(
    tripYardLogId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    vehicleId INT NOT NULL,
    tripId INT NOT NULL,
    checkpointId INT NOT NULL,
    eta DATETIME DEFAULT NULL,
    ata DATETIME DEFAULT NULL,
    isProcessed TINYINT NOT NULL,
    customerNo INT NOT NULL,
    createdBy INT NOT NULL,
    createdOn DATETIME DEFAULT NULL,
    updatedBy INT NOT NULL,
    updatedOn DATETIME DEFAULT NULL,
    isDeleted TINYINT NOT NULL
);

ALTER TABLE `tripYardLog` CHANGE `isProcessed` `isProcessed` TINYINT(4) NOT NULL DEFAULT '0';
ALTER TABLE `tripYardLog` CHANGE `isDeleted` `isDeleted` TINYINT(4) NOT NULL DEFAULT '0';

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_dashboard_tripdetails`$$
CREATE PROCEDURE `get_dashboard_tripdetails`(
    IN customerNoParam INT,
    IN currentDateParam DATE
)

BEGIN
    DECLARE disptachVolumeCount INT;
    DECLARE lrDelayCount INT;
    DECLARE loadingVehiclesCount INT;
    DECLARE intransitVehiclesCount INT;
    DECLARE availableVehiclesCount INT;
    DECLARE yardDetentionDeviationCount INT;
    DECLARE emptyReturnCount INT;
    DECLARE emptyReturnDeviationCount INT;



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

    IF(customerNoParam IS NOT NULL)THEN
        # Todays Total Dispatch Volume(Bags) Count
        SELECT SUM(od.bags) into disptachVolumeCount
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId
        LEFT JOIN deliveryChallan d ON d.orderId = od.orderId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.chitthiDate LIKE CONCAT('%',currentDateParam,'%')
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;

        # LR Delay Count - Chitthi created but delivery challan not created within 60 min
        SELECT COUNT(DISTINCT(td.tripId)) INTO lrDelayCount
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.created_on < DATE_SUB(@istDateTime, INTERVAL 1 HOUR)
        AND od.orderId NOT IN (SELECT dc.orderId FROM deliveryChallan dc WHERE dc.orderId = od.orderId AND dc.customerno = td.customerno)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;

        # Loading Vehicles Count - Chitthi created but delivery challan not created
        SELECT count(DISTINCT(td.tripId)) INTO loadingVehiclesCount
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.orderId NOT IN (SELECT dc.orderId FROM deliveryChallan dc WHERE dc.orderId = od.orderId AND dc.customerno = td.customerno)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;

        # Intransit Vehicles Count - Chitthi And delivery challan created

        SELECT COUNT(DISTINCT(td.tripId)) INTO intransitVehiclesCount
        FROM tripdetails td
        inner JOIN orderDetails od ON od.tripId = td.tripId
        INNER JOIN deliveryChallan dc ON dc.orderId = od.orderId
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;

        # Available Vehicles Count
        SELECT COUNT(v.vehicleid) INTO availableVehiclesCount
        FROM vehicle v
        WHERE (v.customerno= customerNoParam OR customerNoParam IS NULL)
        AND v.vehicleid NOT IN (select tripdetails.vehicleid from tripdetails where is_tripend = 0 AND isdeleted = 0)
        AND v.isdeleted = 0;

        # Yard Deviation Detiontion Count
        SELECT
        count(td.tripid) INTO yardDetentionDeviationCount
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
            select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
        )
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (vehicle.checkpoint_timestamp > od.created_on )
        AND od.customerno= customerNoParam
        AND TIMESTAMPDIFF(MINUTE, od.created_on, vehicle.checkpoint_timestamp) > 90
        AND td.isdeleted = 0
        AND od.isdeleted = 0
        AND td.is_tripend = 0
        order by td.tripid ASC
        ;

        SELECT
        count(td.tripid) INTO emptyReturnCount
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN tripdetail_history tdh on tdh.tripid = td.tripid AND tdh.tripstatusid = (
            select th.tripstatusid from tripdetail_history th WHERE th.tripid = td.tripid and th.tripstatusid = 10 and th.customerno = td.customerno limit 1
        )
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (tdh.statusdate > vehicle.checkpoint_timestamp)
        AND (td.customerno = customerNoParam OR customerNoParam IS NULL)
        AND td.isdeleted = 0
        AND td.is_tripend = 0
        order by td.tripid ASC;

        SELECT
        count(td.tripid) INTO emptyReturnDeviationCount
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN tripdetail_history tdh on tdh.tripid = td.tripid AND tdh.tripstatusid = (
            select th.tripstatusid from tripdetail_history th WHERE th.tripid = td.tripid and th.tripstatusid = 10 and th.customerno = td.customerno limit 1
        )
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (tdh.statusdate > vehicle.checkpoint_timestamp)
        AND (td.customerno = customerNoParam OR customerNoParam IS NULL)
        AND TIMESTAMPDIFF(MINUTE, vehicle.checkpoint_timestamp, tdh.statusdate) > 60
        AND td.isdeleted = 0
        AND td.is_tripend = 0
        order by td.tripid ASC;




        SELECT disptachVolumeCount,lrDelayCount,loadingVehiclesCount,intransitVehiclesCount,availableVehiclesCount,yardDetentionDeviationCount, emptyReturnCount, emptyReturnDeviationCount;

    END IF;


END$$
DELIMITER ;



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

        SELECT DISTINCT(vehicle.vehicleId),vehicle.vehicleNo, C.cgeolat, C.cgeolong,TD.lat,TD.lng,CM.checkpointid,td.tripid,g.groupname,td.tripid,tyl.isProcessed
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN tripdetail_history tdh on tdh.tripid = td.tripid AND tdh.tripstatusid = (
            select th.tripstatusid from tripdetail_history th WHERE th.tripid = td.tripid and th.tripstatusid = 10 and th.customerno = td.customerno limit 1
        )
        INNER JOIN tripdroppoints TD ON TD.tripid = td.tripid AND TD.created_on = (
            SELECT max(TD2.created_on) FROM tripdroppoints TD2 WHERE TD2.vehicleId = vehicle.vehicleId
        )
        INNER JOIN checkpointmanage CM ON CM.vehicleid = vehicle.vehicleid
        INNER JOIN checkpoint C ON C.checkpointid = CM.checkpointid
        INNER JOIN tripYardLog tyl ON tyl.tripid = td.tripid AND tyl.vehicleid = vehicle.vehicleid
        LEFT JOIN `group` g ON g.groupid = vehicle.groupid
        WHERE (vehicle.customerno = customerNoParam)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (tdh.statusdate > vehicle.checkpoint_timestamp)
        AND (td.customerno = customerNoParam)
        AND td.isdeleted = 0
        AND td.is_tripend = 0
        #AND tyl.isProcessed = 0
        Group by td.tripid
        order by td.tripid ASC;


    END IF;

    IF selVehTypeParam = 'tripEnd' THEN

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
DROP PROCEDURE IF EXISTS `update_trip_log`$$
CREATE PROCEDURE `update_trip_log`(

    IN vehicleIdParam INT,
    IN checkpointIdParam INT,
    IN etaParam DATETIME,
    IN ataParam DATETIME,
    IN customerNoParam INT,
    IN userIdParam INT,
    IN todaysdate DATETIME
)
BEGIN
    DECLARE varTripId INT;
    DECLARE varVehicleId INT;
    DECLARE varcheckpointId INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
    IF (customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;
    IF (etaParam = '0000-00-00 00:00:00') THEN
        SET etaParam = NULL;
    END IF;
    IF (ataParam = '0000-00-00 00:00:00') THEN
        SET ataParam = NULL;
    END IF;
    /* Get existing Trip ID And Vehicle Id  */
    SELECT  tripId, vehicleId, checkpointId
    INTO    varTripId, varVehicleId, varcheckpointId
    FROM    tripYardLog
    WHERE   customerno = customerNoParam

    AND     vehicleId = vehicleIdParam
    AND     checkpointId = checkpointIdParam
    AND     isProcessed = 0
    AND     isDeleted = 0
    ORDER BY tripYardLogId DESC
    LIMIT 1;

    START TRANSACTION;

    IF (customerNoParam IS NOT NULL AND varTripId IS NOT NULL AND varVehicleId IS NOT NULL AND varcheckpointId IS NOT NULL) THEN
        UPDATE tripYardLog
        SET
            eta = COALESCE(etaParam, eta),
            ata = COALESCE(ataParam, ata),
            updatedOn = todaysdate,
            updatedBy = userIdParam
        WHERE tripId = varTripId
        AND vehicleId = vehicleIdParam
        AND checkpointId = checkpointIdParam
        AND customerNo = customerNoParam
        AND isProcessed = 0
        AND isDeleted = 0;

        IF(ataParam IS NOT NULL )THEN
            UPDATE tripYardLog
            SET
                isProcessed = 1,
                updatedOn = todaysdate,
                updatedBy = userIdParam
            WHERE tripId = varTripId
            AND vehicleId = vehicleIdParam
            AND checkpointId = checkpointIdParam
            AND customerNo = customerNoParam
            AND isProcessed = 0
            AND isDeleted = 0;
        END IF;

    END IF;
    COMMIT;
END$$
DELIMITER ;







UPDATE  dbpatches
SET     updatedOn = '2018-08-09 20:10:00',isapplied = 1
WHERE   patchid = 595;



