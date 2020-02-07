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
    DECLARE waitingForTripEnd INT;



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
        INNER JOIN vehicle v ON v.vehicleid = td.vehicleid
        INNER JOIN orderDetails od ON od.tripId = td.tripId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.chitthiDate LIKE CONCAT('%',currentDateParam,'%')
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND v.isdeleted = 0
        AND od.isdeleted =0;

        # LR Delay Count - Chitthi created but delivery challan not created within 60 min
        SELECT COUNT(DISTINCT(td.tripId)) INTO lrDelayCount
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
                select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
            )
        INNER JOIN vehicle ON vehicle.vehicleid = td.vehicleid
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
        INNER JOIN vehicle ON vehicle.vehicleid = td.vehicleid
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
        INNER JOIN vehicle ON vehicle.vehicleid = td.vehicleid
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
        count(DISTINCT(td.tripid)) INTO emptyReturnCount
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN tripdroppoints tdp ON tdp.tripid = td.tripid AND tdp.created_on = (
            SELECT max(lastTripDropPoint.created_on) FROM tripdroppoints lastTripDropPoint WHERE lastTripDropPoint.vehicleid = vehicle.vehicleid
        )
        INNER JOIN tripYardLog tyl ON tyl.tripid = td.tripid AND tyl.vehicleid = vehicle.vehicleid
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (td.statusdate > vehicle.checkpoint_timestamp)
        AND (td.customerno = customerNoParam OR customerNoParam IS NULL)
        AND td.isdeleted = 0
        and td.tripstatusid = 10
        AND tyl.isProcessed = 0
        AND td.is_tripend = 0
        Group by td.tripid
        order by td.tripid ASC;

        SELECT
        count(DISTINCT(td.tripid)) INTO emptyReturnDeviationCount
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN tripdroppoints tdp ON tdp.tripid = td.tripid AND tdp.created_on = (
            SELECT max(lastTripDropPoint.created_on) FROM tripdroppoints lastTripDropPoint WHERE lastTripDropPoint.vehicleid = vehicle.vehicleid
        )
        INNER JOIN tripYardLog tyl ON tyl.tripid = td.tripid AND tyl.vehicleid = vehicle.vehicleid
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (td.statusdate > vehicle.checkpoint_timestamp)
        AND (td.customerno = customerNoParam OR customerNoParam IS NULL)
        AND TIMESTAMPDIFF(MINUTE, vehicle.checkpoint_timestamp, td.statusdate) > 60
        AND td.tripstatusid = 10
        AND tyl.isProcessed = 0
        AND td.isdeleted = 0
        AND td.is_tripend = 0
        Group by td.tripid
        order by td.tripid ASC;


        SELECT count(DISTINCT(T1.tripid)) INTO waitingForTripEnd
        FROM tripdetails T1
        INNER JOIN vehicle ON vehicle.vehicleid = T1.vehicleid
        INNER JOIN tripdroppoints tdp ON tdp.vehicleid = vehicle.vehicleid
        INNER JOIN checkpointmanage CM ON CM.vehicleid = tdp.vehicleid
        WHERE T1.customerno = customerNoParam AND
        tdp.created_on = (SELECT max(lastTripDropPoint.created_on) FROM tripdroppoints lastTripDropPoint
        INNER JOIN vehicle vehicle2 ON vehicle2.vehicleid = lastTripDropPoint.vehicleid WHERE vehicle2.vehicleid = vehicle.vehicleid)
        AND T1.is_tripend = 0
        AND T1.tripstatusid = 10
        AND T1.isdeleted = 0
        ANd vehicle.isdeleted = 0;




        SELECT disptachVolumeCount,lrDelayCount,loadingVehiclesCount,intransitVehiclesCount,availableVehiclesCount,yardDetentionDeviationCount, emptyReturnCount, emptyReturnDeviationCount, waitingForTripEnd;

    END IF;


END$$
DELIMITER ;

call get_dashboard_tripdetails(447,'2018-08-20');
