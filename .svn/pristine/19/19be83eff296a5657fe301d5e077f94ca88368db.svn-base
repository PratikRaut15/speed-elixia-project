/*
CreatedBy   :   Sanjeet Shukla
Reason      :   Converted inline query to SP
updatedon   :   18-09-2018
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_freeze_log`$$
CREATE PROCEDURE  `insert_freeze_log`(
      IN vehicleIdParam VARCHAR(50)
    , IN uIdParam INT
    , IN deviceLatParam FLOAT
    , IN deviceLongParam FLOAT
    , IN freezeRadiusInKmParam FLOAT
    , IN isApiParam INT
    , IN fStatusParam INT
    , IN userIdParam INT
    , IN customerNoParam INT
    , IN todaysDateParam DATETIME
    , OUT currentFreezeLogId INT
)
BEGIN
    DECLARE varFreeLogId INT DEFAULT 0;

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
    SET currentFreezeLogId = 0;
    START TRANSACTION;

        IF (customerNoParam = '' OR customerNoParam = 0) THEN
            SET customerNoParam = NULL;
        END IF;

        IF (uIdParam = '' OR uIdParam = 0) THEN
            SET uIdParam = NULL;
        END IF;

        SELECT fid INTO varFreeLogId
        FROM freezelog
        WHERE uid = uIdParam
        AND vehicleid = vehicleIdParam
        AND customerno = customerNoParam
        AND isdeleted = 0;

        #SELECT varFreeLogId;

        IF (customerNoParam IS NOT NULL AND uIdParam IS NOT NULL) THEN

            IF(varFreeLogId = 0 AND fStatusParam = 1)THEN
                BEGIN
                    UPDATE unit set is_freeze=1 where uid = uIdParam;
                END;
                BEGIN
                    INSERT INTO freezelog (
                            uid,
                            vehicleid,
                            devicelat,
                            devicelong,
                            freezeRadiusInKm,
                            customerno,
                            createdby,
                            createdon,
                            updatedby,
                            updatedon,
                            is_api
                        )
                    VALUES (
                            uIdParam
                            ,vehicleIdParam
                            ,deviceLatParam
                            ,deviceLongParam
                            ,freezeRadiusInKmParam
                            ,customerNoParam
                            ,userIdParam
                            ,todaysDateParam
                            ,userIdParam
                            ,todaysDateParam
                            ,isApiParam
                        );
                    SET currentFreezeLogId = LAST_INSERT_ID();
                 END;
            ELSEIF(varFreeLogId > 0 AND fStatusParam = 0)THEN
                BEGIN
                    UPDATE unit set is_freeze=0 where uid = uIdParam;
                END;

                UPDATE  freezelog
                set     isdeleted=1,
                        updatedon=todaysDateParam,
                        updatedby= userIdParam
                where   fid = varFreeLogId
                AND uid= uIdParam AND isdeleted=0;

                SET currentFreezeLogId = 1;
            END IF;

        END IF;



    COMMIT;


END$$
DELIMITER ;

CALL insert_freeze_log('707','6336','18.922223','72.973948','0.5','1','0','7937','135','2018-09-18 16:16:13',@currentFreezeLogId);
SELECT @currentFreezeLogId;

