DELIMITER $$
DROP PROCEDURE IF EXISTS `update_user_smslock`$$
CREATE PROCEDURE `update_user_smslock`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN useridParam INT(11)
    ,IN smscountParam INT(11)
    ,IN usersmslockParam INT(11)
     )

BEGIN
    DECLARE smscountVar INT(11);
    DECLARE updatedbyVar INT(11);

    SELECT  `sms_count` 
    INTO    smscountVar 
    FROM    `user` 
    WHERE   userid = useridParam;

    SELECT      updatedby
    INTO        updatedbyVar
    FROM        smslocklog 
    WHERE       userid = useridParam
    ORDER BY    logid DESC
    LIMIT       1;
    
    START TRANSACTION;
    BEGIN

        UPDATE  `user` 
        SET     sms_count = sms_count + smscountParam 
        WHERE   userid = useridParam 
        AND     isdeleted = 0;

        IF smscountVar >= usersmslockParam AND updatedbyVar IS NOT NULL THEN
        BEGIN

            UPDATE  `user` 
            SET     sms_lock = 1 
            WHERE   userid = useridParam;

            INSERT INTO `smslocklog` (`customerno`
                ,`userid`
                ,`vehicleid`
                ,`createdby`
                ,`createdon`)
            VALUES (customernoParam
                ,useridParam
                ,0
                ,0
                ,todaysdateParam);

        END;
        END IF;

    END;
    COMMIT;
    
END$$
DELIMITER ;