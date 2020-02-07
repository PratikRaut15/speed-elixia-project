INSERT INTO speed.dbpatches (
patchid ,
patchdate ,
appliedby ,
patchdesc ,
isapplied
)
VALUES (
'566', '2018-06-01 17:33:15', 'Mrudang Vora', 'SBM Listener Change', '0'
);

ALTER TABLE unit ADD hasDeliverySwitch TINYINT(1) NOT NULL DEFAULT '0' AFTER consignee_id;

DELIMITER $$
DROP PROCEDURE IF EXISTS listener_insert_trip_droppoints$$
CREATE PROCEDURE listener_insert_trip_droppoints(
    IN vehicleIdParam INT
    ,IN latParam DECIMAL(9,6)
    ,IN lngParam DECIMAL(9,6)
    ,IN isDroppointParam TINYINT(1)
    ,IN isTripEndParam TINYINT(1)
    ,IN customerNoParam INT
    ,IN todaysDateParam DATETIME
    ,OUT insertedIdParam INT
)
BEGIN
    DECLARE tripIdVar INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        SET insertedIdParam = 0;
    END;

    SELECT  tripid
    INTO    tripIdVar
    FROM    tripdetails
    WHERE   vehicleid = vehicleIdParam
    AND     customerno = customerNoParam
    AND     tripstatusid != 10
    AND     isdeleted = 0
    AND     is_tripend = 0
    ORDER BY tripid DESC
    LIMIT 1;

    START TRANSACTION;
        IF tripIdVar IS NOT NULL THEN
            IF isDroppointParam = 1 THEN
                BEGIN
                    INSERT INTO tripdroppoints
                    (
                        tripid
                        ,vehicleid
                        ,lat
                        ,lng
                        ,customerno
                        ,created_on
                        ,updated_on
                    )
                    VALUES
                    (
                        tripIdVar
                        ,vehicleIdParam
                        ,latParam
                        ,lngParam
                        ,customerNoParam
                        ,todaysDateParam
                        ,todaysDateParam
                    );

                    SET insertedIdParam = LAST_INSERT_ID();
                END;
            END IF;
            IF isTripEndParam = 1 THEN
                BEGIN
                    UPDATE tripdetails SET tripstatusid = 10, updatedtime = todaysDateParam WHERE tripid = tripIdVar;
                END;
            END IF;
        END IF;
    COMMIT;
END$$
DELIMITER ;

