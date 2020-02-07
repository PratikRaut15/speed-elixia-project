DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_trip_lr_mapping`$$
CREATE  PROCEDURE `insert_trip_lr_mapping`(
    IN tripIdParam INT
    , IN lrNoParam VARCHAR(25)
    , IN triplognoParam VARCHAR(20)
    , IN lrDateTimeParam DATETIME
    , IN consignorIdParam INT
    , IN consigneeIdParam INT
    , IN customerNoParam INT
    , IN userIdParam INT
    , IN todaysDate DATETIME
    )
BEGIN




        INSERT INTO triplrmapping(
            tripid
            , lrno
            , orderno
            , lrdatetime
            , lrconsignorid
            , lrconsigneeid
            , customerno
            , created_by
            , created_on
        )
        VALUES (
            tripIdParam
            , lrNoParam
            , triplognoParam
            , lrDateTimeParam
            , consignorIdParam
            , consigneeIdParam
            , customerNoParam
            , userIdParam
            , todaysDate
        );


END$$
DELIMITER ;

CALL insert_trip_lr_mapping('16471','5467987','1234A_test','2018-05-25 14:22:00','500','503','447','6991','2018-05-29 17:25:43');
