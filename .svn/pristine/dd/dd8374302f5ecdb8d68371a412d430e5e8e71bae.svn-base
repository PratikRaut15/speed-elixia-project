DELIMITER $$
DROP PROCEDURE IF EXISTS `update_vehicle_smslock`$$
CREATE PROCEDURE `update_vehicle_smslock`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN smscountParam INT(11)
    ,IN vehsmslockParam INT(11)
     )

BEGIN
    DECLARE smscountVar INT(11);
    DECLARE updatedbyVar INT(11);
    
    SELECT  `sms_count` 
    INTO    smscountVar 
    FROM    `vehicle` 
    WHERE   vehicleid = vehicleidParam;

    SELECT      updatedby
    INTO        updatedbyVar
    FROM        smslocklog 
    WHERE       vehicleid = vehicleidParam
    ORDER BY    logid DESC
    LIMIT       1;

    START TRANSACTION;
    BEGIN

        UPDATE  `vehicle` 
        SET     sms_count = sms_count + smscountParam 
        WHERE   vehicleid = vehicleidParam 
        AND     isdeleted = 0;

        IF smscountVar >= vehsmslockParam AND updatedbyVar IS NOT NULL THEN
        BEGIN

            UPDATE  `vehicle` 
            SET     sms_lock = 1 
            WHERE   vehicleid = vehicleidParam;

            INSERT INTO `smslocklog` (
                `customerno`
                ,`userid`
                ,`vehicleid`
                ,`createdby`
                ,`createdon`)
            VALUES (customernoParam
                ,0
                ,vehicleidParam
                ,0
                ,todaysdateParam);

        END;
        END IF;
    
    END;
    COMMIT;

END$$
DELIMITER ;