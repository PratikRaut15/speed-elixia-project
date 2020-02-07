
DELIMITER $$
DROP PROCEDURE IF EXISTS `updateDumpShipmentno`$$
CREATE PROCEDURE updateDumpShipmentno(
    IN vehicleidParam INT
    , IN shipmentnoParam VARCHAR(30)
    , IN startDateTimeParam DATETIME
    , IN endDateTimeParam DATETIME
    , IN customernoParam INT
    , OUT isUpdatedOut TINYINT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */

        SET isUpdatedOut = 0;
    END;

    SET isUpdatedOut = 0;

    IF (vehicleidParam = 0) THEN
        SET vehicleidParam = NULL;
    END IF;

    IF (shipmentnoParam = '') THEN
        SET shipmentnoParam = NULL;
    END IF;

    IF (startDateTimeParam = '0000-00-00 00:00:00') THEN
        SET startDateTimeParam = NULL;
    END IF;

    IF (endDateTimeParam = '0000-00-00 00:00:00') THEN
        SET endDateTimeParam = NULL;
    END IF;

    IF (customernoParam = 0) THEN
        SET customernoParam = NULL;
    END IF;

    START TRANSACTION;

        IF (vehicleidParam IS NOT NULL 
            AND shipmentnoParam IS NOT NULL
            AND startDateTimeParam IS NOT NULL
            AND endDateTimeParam IS NOT NULL
            AND customernoParam IS NOT NULL) THEN

            UPDATE  mdlzRealTimeDump 
            SET     shipmentno = shipmentnoParam
            WHERE   customerno = customernoParam
            AND     vehicleid = vehicleidParam
            AND     (lastupdated BETWEEN startDateTimeParam AND endDateTimeParam)
            AND     shipmentno IS NULL;

            SET isUpdatedOut = 1;

        END IF;
    COMMIT;
END$$
DELIMITER ;