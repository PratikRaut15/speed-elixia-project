INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'628', '2018-10-24 16:00:00', 'Manasvi Thakur','To Update get_dashbboard_vehicles SP', '0');


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

AND v.vehicleid NOT IN (select tripdetails.vehicleid from tripdetails where is_tripend = 0 AND isdeleted = 0)
AND v.isdeleted =0;
END IF;

IF vehicleTypeParam = 'Intrans'
THEN
SELECT DISTINCT(td.tripId) ,vehicle.vehicleNo,devices.devicelat,devices.devicelong
        FROM tripdetails td
        inner JOIN orderDetails od ON od.tripId = td.tripId
        INNER JOIN deliveryChallan dc ON dc.orderId = od.orderId
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        INNER JOIN devices ON devices.uid = vehicle.uid
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;
END IF;

IF vehicleTypeParam = 'Load'
THEN

SELECT DISTINCT(td.tripId),vehicle.vehicleNo
        FROM tripdetails td
        INNER JOIN orderDetails od ON od.tripId = td.tripId
        INNER JOIN vehicle ON vehicle.vehicleId = td.vehicleId
        WHERE (td.customerno= customerNoParam OR customerNoParam IS NULL)
        AND od.orderId NOT IN (SELECT dc.orderId FROM deliveryChallan dc WHERE dc.orderId = od.orderId AND dc.customerno = td.customerno)
        AND od.customerno= customerNoParam
        AND td.is_tripend = 0
        AND td.isdeleted =0
        AND od.isdeleted =0;

END IF;
END$$


DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-10-24 16:00:00'
        ,isapplied =1
WHERE   patchid = 628;