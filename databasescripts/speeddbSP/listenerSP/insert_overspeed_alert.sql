DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_overspeed_alert`$$
CREATE PROCEDURE `insert_overspeed_alert`(
    IN vehicleidParam INT
    , IN custnoParam INT
    , IN speedParam TINYINT UNSIGNED
    , IN overspeedLimitParam TINYINT UNSIGNED
    , IN devicelatParam DECIMAL(9,6)
    , IN devicelongParam DECIMAL(9,6)
    , IN overspeedStatusParam TINYINT(1) UNSIGNED
    , IN vehicleNoParam VARCHAR(40)
    , IN todaysDate DATETIME
)
BEGIN

    DECLARE osalertidVar INT(11) DEFAULT NULL;
    DECLARE countVar INT;
    DECLARE last_checkVar DATETIME;
    DECLARE last_statusVar TINYINT;
    DECLARE email_statusVar TINYINT;
    DECLARE lastupdatedVar DATETIME;
    DECLARE currstatusVar TINYINT;

    DECLARE overspeedMsg VARCHAR(100) DEFAULT '';
    DECLARE isOverspeed TINYINT(1) DEFAULT NULL;
    
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

    SET @overspeedYesMsg = CONCAT(vehicleNoParam, ' overspeed');
    SET @overspeedNormalMsg = CONCAT(vehicleNoParam, ' is running normal');
    SET @overspeedAlertType = 5;

    SELECT  osalertid
    INTO    osalertidVar
    FROM    overspeedalert
    WHERE   vehicleid = vehicleidParam
    limit   1;
	    
    START TRANSACTION;
	
        IF (osalertidVar IS NULL) THEN
        BEGIN
            INSERT INTO overspeedalert(`vehicleid`
                ,`last_status`
                ,`last_check`
                ,`count`
                ,`status`
                ,`customerno`)
            VALUES(vehicleidParam
                ,0
                ,todaysDate
                ,0
                ,0
                ,custnoParam);
        END;
        END IF;
		
        SELECT  osa.count 
                , osa.last_check
                , osa.last_status
                , osa.`status`
                , d.lastupdated 
        INTO	countVar
                ,last_checkVar
                ,last_statusVar
                ,email_statusVar
                ,lastupdatedVar
        FROM    devices AS d
        INNER JOIN unit u ON u.uid = d.uid
        INNER JOIN vehicle v on v.vehicleid = u.vehicleid
        INNER JOIN overspeedalert osa ON osa.vehicleid = v.vehicleid
        where   u.trans_statusid NOT IN (10,22)
        AND	v.vehicleid = vehicleidParam
        LIMIT   1;

        IF (speedParam >= overspeedLimitParam) THEN
            SET currstatusVar = 1;
        END IF;

        IF (speedParam < overspeedLimitParam) THEN
            SET currstatusVar = 0;
        END IF;

        IF (lastupdatedVar > last_checkVar) THEN
        BEGIN

            IF (last_statusVar = currstatusVar AND countVar < 5) THEN
                SET countVar = countVar + 1;
            ELSE
                SET countVar = 1;
            END IF;

            UPDATE  overspeedalert 
            Set     `count` = countVar
                    ,`last_status` = currstatusVar
            WHERE   vehicleid = vehicleIdParam 
            AND     customerno = custnoParam;

        END;
        END IF;

        Update  overspeedalert 
        SET     last_check = lastupdatedVar 
        WHERE   vehicleid = vehicleIdParam 
        AND     customerno = custnoParam;

        IF (currstatusVar = 1 AND countVar = 5 AND email_statusVar = 0) THEN
        BEGIN
            SET overspeedMsg = @overspeedYesMsg;
            SET isOverspeed = 1;
        END;
        END IF;
        
        IF (currstatusVar = 0 AND countVar = 5 AND email_statusVar = 1) THEN    
        BEGIN
            SET overspeedMsg = @overspeedNormalMsg;
            SET isOverspeed = 0;
        END;
        END IF;

        IF (overspeedMsg != '') THEN
        BEGIN
            INSERT INTO comqueue(customerno
                    , vehicleid
                    , devlat
                    , devlong
                    , `type`
                    , `status`
                    , message
                    , timeadded)
            VALUES(custnoParam
                    , vehicleidParam
                    , devicelatParam
                    , devicelongParam
                    , @overspeedAlertType
                    , overspeedStatusParam
                    , overspeedMsg
                    , todaysDate);

            UPDATE  eventalerts
            SET     overspeed =	isOverspeed
            WHERE   vehicleid =	vehicleIdParam
            AND     customerno = custnoParam;

            Update  overspeedalert 
            Set     `count` = 0
                    , status = isOverspeed 
            WHERE   vehicleid = vehicleIdParam
            AND     customerno = custnoParam;

            IF isOverspeed = 1 THEN
            BEGIN
                UPDATE  dailyreport
                SET 	overspeed = overspeed + 1
                WHERE 	vehicleid = vehicleIdParam
                AND 	customerno = custnoParam;
            END;
            END IF;
        END;
        END IF;
       
    COMMIT;
    
END$$
DELIMITER ;

-- CALL insert_overspeed_alert(7818,64,100,91,27.997025,72.256783,1,'RJ 07 UA 6044','2018-06-26 17:08:00');
