INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'590', '2018-08-01 19:50:00', 'Manasvi Thakur', 'SBM Api', '0');

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
SELECT DISTINCT(vehicle.vehicleId) ,vehicle.vehicleNo
FROM tripdetails T1 LEFT JOIN orderDetails O ON O.tripId = T1.tripId
LEFT JOIN deliveryChallan D ON D.orderId = O.orderId
LEFT JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
WHERE T1.customerno= customerNoParam
AND D.orderId NOT IN (SELECT D2.orderId FROM deliveryChallan D2 WHERE D2.customerno = T1.customerno) ;
END IF;
END$$


DELIMITER ;


UPDATE  dbpatches
SET     patchdate = NOW(),isapplied = 1
WHERE   patchid = 590;
