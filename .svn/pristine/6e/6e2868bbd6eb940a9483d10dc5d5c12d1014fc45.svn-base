DELIMITER $$
DROP TRIGGER IF EXISTS before_route_update$$
CREATE TRIGGER before_route_update BEFORE UPDATE ON route
FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');


     IF (NEW.`routename` <> OLD.`routename` OR NEW.`routeTat` <> OLD.`routeTat` OR NEW.`isdeleted` <> OLD.`isdeleted`) THEN

        INSERT INTO routeHistory
        (
            routeid,
            routename,
            routeTat,
            customerno,
            userid,
            isdeleted,
            timestamp,
            devicekey,
            androidid,
            isregister,
            insertedDatetime
        )
        VALUES
        (
            OLD.routeid,
            OLD.routename,
            OLD.routeTat,
            OLD.customerno,
            OLD.userid,
            OLD.isdeleted,
            OLD.timestamp,
            OLD.devicekey,
            OLD.androidid,
            OLD.isregister,
            @istDateTime
        );

     END IF;



END$$
DELIMITER ;
