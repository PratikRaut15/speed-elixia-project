DELIMITER $$
DROP PROCEDURE IF EXISTS `get_tripdetails_data`$$
CREATE PROCEDURE `get_tripdetails_data`(
    IN customerNoParam INT,
    IN reportTypeParam VARCHAR(50),
    IN currentDateParam DATE
)
BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
    IF customerNoParam = 0 THEN
        SET customerNoParam = NULL;
    END IF;

    IF reportTypeParam = '' THEN
        SET reportTypeParam = NULL;
    END IF;

    IF(customerNoParam IS NOT NULL AND reportTypeParam IS NOT NULL)THEN

        IF(reportTypeParam = 'volumeDispatched')THEN
            SELECT DISTINCT(td.tripId), td.triplogno, v.vehicleno, od.chitthiNo, od.created_on as chittiCreationDate, od.bags
            #SELECT SUM(od.bags) into disptachVolumeCount
            FROM tripdetails td
            INNER JOIN vehicle v ON v.vehicleid = td.vehicleid
            INNER JOIN orderDetails od ON od.tripId = td.tripId
            WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
            AND od.chitthiDate LIKE CONCAT('%',currentDateParam,'%')
            AND od.customerno= customerNoParam
            AND td.is_tripend = 0
            AND td.isdeleted =0
            AND v.isdeleted = 0
            AND od.isdeleted =0
            Order By td.tripid ASC;
        ELSEIF(reportTypeParam = 'lrDelayed')THEN
            SELECT DISTINCT(td.tripId), td.triplogno, vehicle.vehicleno, od.created_on as chittiCreationDate
            FROM tripdetails td
            INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
                select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
            )
            INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
            WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
            AND od.created_on < DATE_SUB(@istDateTime, INTERVAL 1 HOUR)
            AND od.orderId NOT IN (SELECT dc.orderId FROM deliveryChallan dc WHERE dc.orderId = od.orderId AND dc.customerno = td.customerno)
            AND od.customerno= customerNoParam
            AND td.is_tripend = 0
            AND td.isdeleted =0
            AND od.isdeleted =0;
        ELSEIF(reportTypeParam = 'yardDetentionDeviation')THEN
            SELECT
            DISTINCT(td.tripId), td.triplogno, vehicle.vehicleno, od.created_on as chittiCreationDate, c.cname as yardName, vehicle.checkpoint_timestamp as yardOutTime
            FROM vehicle
            INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
            INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
                select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
            )
            INNER JOIN checkpoint c on c.checkpointid = vehicle.checkpointId
            WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
            AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
            AND (vehicle.checkpoint_timestamp > od.created_on )
            AND od.customerno= customerNoParam
            AND TIMESTAMPDIFF(MINUTE, od.created_on, vehicle.checkpoint_timestamp) > 90
            AND td.isdeleted = 0
            AND od.isdeleted = 0
            AND td.is_tripend = 0
            order by td.tripid ASC;
        ELSEIF(reportTypeParam = 'emptyReturnDeviation')THEN
            SELECT
            DISTINCT(td.tripId), td.triplogno, vehicle.vehicleno
            FROM vehicle
            INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
            INNER JOIN tripdroppoints tdp ON tdp.tripid = td.tripid AND tdp.created_on = (
                SELECT max(lastTripDropPoint.created_on) FROM tripdroppoints lastTripDropPoint WHERE lastTripDropPoint.vehicleId = vehicle.vehicleId
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
        END IF;

    END IF;
END$$
DELIMITER ;
call get_tripdetails_data(447,'volumeDispatched','2018-08-20');
