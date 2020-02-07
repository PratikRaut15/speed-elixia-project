DELIMITER $$
DROP TRIGGER IF EXISTS before_routeman_delete$$
CREATE TRIGGER before_routeman_delete BEFORE DELETE ON routeman
FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');

    INSERT INTO routemanHistory
        (
            rmid,
            routeid,
            checkpointid,
            timetaken,
            distance,
            sequence,
            etaStatus,
            eta,
            etd,
            r_eta,
            r_etd,
            customerno,
            userid,
            isdeleted,
            timestamp,
            insertedDatetime
        )
    VALUES
        (
            OLD.rmid,
            OLD.routeid,
            OLD.checkpointid,
            OLD.timetaken,
            OLD.distance,
            OLD.sequence,
            OLD.etaStatus,
            OLD.eta,
            OLD.etd,
            OLD.r_eta,
            OLD.r_etd,
            OLD.customerno,
            OLD.userid,
            OLD.isdeleted,
            OLD.timestamp,
            @istDateTime
        );

END$$
DELIMITER ;
