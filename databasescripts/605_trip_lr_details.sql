INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'605', '2018-08-27 15:34:11', 'Shrikant Suryawanshi', 'SBM - Trip Lr Details', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_trip_lr_details`$$
CREATE PROCEDURE `get_trip_lr_details`(
    IN tripIdParam INT,
    IN customerNoParam INT
)
BEGIN
    DECLARE varChitthiCreation DATETIME;
    DECLARE varLrCreation DATETIME;
    DECLARE varYardCheckout DATETIME;
    DECLARE varYardCheckin DATETIME;
    DECLARE varYardDetention DATETIME;
    DECLARE varEmptyReturnDeviation DATETIME;



    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
    IF customerNoParam = 0 THEN
        SET customerNoParam = NULL;
    END IF;



    IF(customerNoParam IS NOT NULL )THEN

       SELECT DISTINCT(od.created_on) INTO varChitthiCreation
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
                select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
            )
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND (td.tripid= tripIdParam OR tripIdParam IS NULL)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;


        SELECT
        DISTINCT(dc.created_on) INTO varLrCreation
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
                select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
            )
        INNER JOIN deliveryChallan dc ON dc.orderId = od.orderId AND dc.deliveryChallanId = (
                select dc1.deliveryChallanId from deliveryChallan dc1 WHERE dc1.orderId = od.orderId AND dc1.isdeleted = 0 Order by dc1.created_on ASC limit 1
            )
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND (td.tripid= 20696)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;

        SELECT
        DISTINCT(vehicle.checkpoint_timestamp) INTO varYardCheckout
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
            select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
        )
        INNER JOIN checkpoint c on c.checkpointid = vehicle.checkpointId
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND (td.tripid= tripIdParam OR tripIdParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 1 AND vehicle.isdeleted = 0
        AND (vehicle.checkpoint_timestamp > od.created_on )
        AND od.customerno= customerNoParam
        AND td.isdeleted = 0
        AND od.isdeleted = 0
        AND td.is_tripend = 0
        order by td.tripid ASC;

        SELECT
        DISTINCT(vehicle.checkpoint_timestamp) INTO varYardCheckin
        FROM vehicle
        INNER JOIN tripdetails td on td.vehicleid = vehicle.vehicleid
        INNER JOIN orderDetails od ON od.tripId = td.tripId AND od.orderid = (
            select o.orderid from orderDetails o where o.tripid = td.tripid AND o.customerno = td.customerno AND o.isdeleted = 0 Order by o.created_on ASC limit 1
        )
        INNER JOIN checkpoint c on c.checkpointid = vehicle.checkpointId
        WHERE (vehicle.customerno = customerNoParam OR customerNoParam IS NULL)
        AND (td.tripid= tripIdParam OR tripIdParam IS NULL)
        AND vehicle.checkpointId <> 0 AND chkpoint_status = 0 AND vehicle.isdeleted = 0
        AND (vehicle.checkpoint_timestamp > od.created_on )
        AND od.customerno= customerNoParam
        AND td.tripstatusid = 10
        AND td.isdeleted = 0
        AND od.isdeleted = 0
        AND td.is_tripend = 0
        order by td.tripid ASC;




        SELECT varChitthiCreation,varLrCreation,varYardCheckout,varYardCheckin;

    END IF;
END$$
DELIMITER ;


UPDATE  dbpatches
SET     updatedOn = NOW(),isapplied = 1
WHERE   patchid = 605;
