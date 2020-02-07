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
            dp.created_on as createdonParam,v.checkpoint_timestamp
    FROM    tripdroppoints dp
    INNER JOIN vehicle v on v.vehicleid = dp.vehicleid
    WHERE   (dp.tripId = tripIdParam OR tripIdParam IS NULL)
    AND     (dp.customerno = customerNoParam OR customerNoParam IS NULL)
    AND     dp.isdeleted = 0
    AND     v.chkpoint_status = 1;
    
    

END$$
DELIMITER ;

-- CALL get_trip_droppoints(20455,447);
