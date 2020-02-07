DELIMITER $$
DROP TRIGGER IF EXISTS before_vehiclerouteman_delete$$
CREATE TRIGGER before_vehiclerouteman_delete BEFORE DELETE ON vehiclerouteman
FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');

    INSERT INTO vehicleroutemanHistory
        (
            vrmanid,
            routeid,
            vehicleid,
            customerno,
            userid,
            isdeleted,
            timestamp,
            insertedDatetime
        )
    VALUES
        (
            OLD.vrmanid,
            OLD.routeid,
            OLD.vehicleid,
            OLD.customerno,
            OLD.userid,
            OLD.isdeleted,
            OLD.timestamp,
            @istDateTime
        );

END$$
DELIMITER ;
