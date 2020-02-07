INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'622', '2018-10-06 17:40:00', 'Manasvi Thakur','To update get_trip_droppoints SP', '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_trip_droppoints`$$
CREATE PROCEDURE `get_trip_droppoints`(
    IN tripIdParam INT,
    IN customerNoParam INT
)
BEGIN

    IF(tripIdParam = 0) THEN
        SET tripIdParam = NULL;
    END IF;

    IF(customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;


    SELECT
                dp.tripid,
                dp.vehicleid,
                dp.lat,
                dp.lng,
                dp.created_on,v.checkpoint_timestamp,td.created_on as createdonParam
    FROM        tripdroppoints dp
    INNER JOIN vehicle v on v.vehicleid = dp.vehicleid
    INNER JOIN tripdroppoints td ON td.vehicleid = v.vehicleid   
    WHERE      (dp.tripId = tripIdParam OR tripIdParam IS NULL)
    AND        (dp.customerno = customerNoParam OR customerNoParam IS NULL)
    AND        dp.isdeleted = 0
    AND        v.chkpoint_status = 1;
    


END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2018-10-06 17:40:00'
        ,isapplied =1
WHERE   patchid = 622;