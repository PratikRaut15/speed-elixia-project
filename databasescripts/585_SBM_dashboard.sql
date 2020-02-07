
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'585', 'NOW()', 'Manasvi Thakur', 'SBM dashboard', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_dashboard_tripdetails`$$
CREATE PROCEDURE `get_dashboard_tripdetails`(
    IN customerNoParam INT(10),
    IN currentDateParam DATE,
    OUT totalVolume INT(11),
    OUT totalDetension INT(11),
    OUT loadingVehicles INT(11),
    OUT inTrasitVheicles INT(11),
    OUT availableVehicles INT(11)
)

BEGIN
  DECLARE totalVolumeVal INT;
    DECLARE totalDetensionVal INT;
    DECLARE loadingVal INT;
    DECLARE inTrasitVal INT;
    DECLARE availableVal INT;
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



SELECT SUM(O.bags) into totalVolumeVal
FROM tripdetails T1
INNER JOIN orderDetails O ON O.tripId = T1.tripId
WHERE T1.statusdate LIKE CONCAT('%',currentDateParam,'%')
AND T1.customerno= customerNoParam
AND T1.isdeleted =0;
SET totalVolume = totalVolumeVal;



SELECT COUNT(DISTINCT(T1.tripId)) INTO totalDetensionVal
FROM tripdetails T1
RIGHT JOIN orderDetails O ON O.tripId = T1.tripId
LEFT JOIN deliveryChallan D ON D.orderId = O.orderId
WHERE T1.customerno= customerNoParam
AND O.created_on < DATE_SUB(@istDateTime, INTERVAL 1 HOUR)
AND D.orderId NOT IN (SELECT D2.orderId FROM deliveryChallan D2 WHERE D2.customerno = T1.customerno) AND T1.isdeleted =0;

SET totalDetension = totalDetensionVal;

SELECT COUNT(DISTINCT(T1.tripId)) INTO loadingVal
FROM tripdetails T1 LEFT JOIN orderDetails O ON O.tripId = T1.tripId
LEFT JOIN deliveryChallan D ON D.orderId = O.orderId
LEFT JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
WHERE T1.customerno= customerNoParam
AND D.orderId NOT IN (SELECT D2.orderId FROM deliveryChallan D2 WHERE D2.customerno = T1.customerno) AND T1.isdeleted =0;

SET loadingVehicles = loadingVal;


SELECT COUNT(DISTINCT(T1.tripId)) INTO inTrasitVal
FROM tripdetails T1
inner JOIN orderDetails O ON O.tripId = T1.tripId
INNER JOIN deliveryChallan D ON D.orderId = O.orderId
INNER JOIN vehicle ON vehicle.vehicleId = T1.vehicleId
WHERE T1.customerno= customerNoParam
AND D.orderId  IN (SELECT D2.orderId FROM deliveryChallan D2 WHERE D2.customerno = T1.customerno) AND T1.isdeleted =0;

SET inTrasitVheicles = inTrasitVal;




SELECT COUNT(v.vehicleid) INTO availableVal
FROM vehicle v
WHERE v.customerno = customerNoParam
AND v.isdeleted = 0
AND v.vehicleid NOT IN (select tripdetails.vehicleid from tripdetails where is_tripend = 0 AND isdeleted = 0)
AND v.isdeleted =0;


SET availableVehicles = availableVal;
END$$
DELIMITER ;


UPDATE 	dbpatches
SET 	patchdate = NOW()
	, isapplied =1
WHERE 	patchid = 585;
