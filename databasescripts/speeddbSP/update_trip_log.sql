DELIMITER $$
DROP PROCEDURE IF EXISTS `update_trip_log`$$
CREATE PROCEDURE `update_trip_log`(

    IN vehicleIdParam INT,
    IN checkpointIdParam INT,
    IN etaParam DATETIME,
    IN ataParam DATETIME,
    IN customerNoParam INT,
    IN userIdParam INT,
    IN todaysdate DATETIME
)
BEGIN
    DECLARE varTripId INT;
    DECLARE varVehicleId INT;
    DECLARE varcheckpointId INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
    IF (customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;
    IF (etaParam = '0000-00-00 00:00:00') THEN
        SET etaParam = NULL;
    END IF;
    IF (ataParam = '0000-00-00 00:00:00') THEN
        SET ataParam = NULL;
    END IF;
    /* Get existing Trip ID And Vehicle Id  */
    SELECT  tripId, vehicleId, checkpointId
    INTO    varTripId, varVehicleId, varcheckpointId
    FROM    tripYardLog
    WHERE   customerno = customerNoParam

    AND     vehicleId = vehicleIdParam
    AND     checkpointId = checkpointIdParam
    AND     isProcessed = 0
    AND     isDeleted = 0
    ORDER BY tripYardLogId DESC
    LIMIT 1;

    START TRANSACTION;

    IF (customerNoParam IS NOT NULL AND varTripId IS NOT NULL AND varVehicleId IS NOT NULL AND varcheckpointId IS NOT NULL) THEN
        UPDATE tripYardLog
        SET
            eta = COALESCE(etaParam, eta),
            ata = COALESCE(ataParam, ata),
            updatedOn = todaysdate,
            updatedBy = userIdParam
        WHERE tripId = varTripId
        AND vehicleId = vehicleIdParam
        AND checkpointId = checkpointIdParam
        AND customerNo = customerNoParam
        AND isProcessed = 0
        AND isDeleted = 0;

        IF(ataParam IS NOT NULL )THEN
            UPDATE tripYardLog
            SET
                isProcessed = 1,
                updatedOn   = todaysdate,
                updatedBy   = userIdParam
            WHERE tripId    = varTripId
                AND vehicleId   = vehicleIdParam
                #AND checkpointId = checkpointIdParam
                AND customerNo = customerNoParam
                AND isProcessed = 0
                AND isDeleted = 0;

            UPDATE tripDetails
                SET tripstatusid = '12',    
                updatedtime      = ataParam,
            WHERE tripId         = varTripId
                AND customerNo   = customerNoParam
                AND isDeleted    = 0;

        END IF;

    END IF;
    COMMIT;
END$$
DELIMITER ;

CALL update_trip_log(100,5,'','2018-08-11 12:12:00', 447, 4, '2018-08-11 05:00:00');
