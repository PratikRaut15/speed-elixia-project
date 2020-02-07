INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'570', '2018-06-27 12:27:00', 'Arvind Thakur', 'overspeed fluctuation issue', '0'
);


CREATE TABLE `overspeedalert` (
  `osalertid` int(11) PRIMARY KEY AUTO_INCREMENT,
  `vehicleid` int(11) NOT NULL,
  `last_status` tinyint(4) NOT NULL,
  `last_check` datetime NOT NULL,
  `count` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `customerno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


DELIMITER $$
DROP PROCEDURE IF EXISTS `listener_insertupdate_alerts`$$
CREATE PROCEDURE `listener_insertupdate_alerts`(
	IN timeDiffParam INT
	, IN devicelatParam DECIMAL(9,6)
	, IN devicelongParam DECIMAL(9,6)
	, IN tamperParam TINYINT(1) UNSIGNED
	, IN tamperStatusParam TINYINT(1) UNSIGNED
	, IN powercutParam TINYINT(1) UNSIGNED
	, IN powercutStatusParam TINYINT(1) UNSIGNED
	, IN speedParam TINYINT UNSIGNED
	, IN overspeedLimitParam TINYINT UNSIGNED
	, IN overspeedStatusParam TINYINT(1) UNSIGNED
	, IN commandkeyParam TINYINT(1) UNSIGNED
	, IN statusParam VARCHAR(2)
	, IN acsensorParam TINYINT(1) UNSIGNED
	, IN is_ac_oppParam TINYINT(1) UNSIGNED
	, IN acStatusParam TINYINT(1) UNSIGNED
	, IN acCountParam TINYINT(1) UNSIGNED
	, IN acTimeParam DATETIME
	, IN digitalioParam TINYINT(1) UNSIGNED
	, IN digitalioupdatedParam DATETIME
	, IN doorsensorParam TINYINT(1) UNSIGNED
	, IN is_door_oppParam TINYINT(1) UNSIGNED
	, IN door_statusParam TINYINT(1) UNSIGNED
	, IN swvParam VARCHAR(15)
	, IN analog4Param INT
	, IN simcardnoParam VARCHAR(20)
	, IN vehicleNoParam VARCHAR(40)
	, IN vehicleidParam INT
	, IN custnoParam INT
	, IN todaysDate DATETIME
	, OUT isUpdated TINYINT(1)
)
BEGIN
	DECLARE tamperMsg VARCHAR(100) DEFAULT '';
	DECLARE isTampered TINYINT(1) DEFAULT NULL;

	DECLARE powercutMsg VARCHAR(100) DEFAULT '';
	DECLARE isPowercut TINYINT(1) DEFAULT NULL;

	DECLARE isVehicleImmobilized TINYINT(1) DEFAULT NULL;

	DECLARE acMsg VARCHAR(100) DEFAULT '';
	DECLARE isAcOn TINYINT(1) DEFAULT NULL;
	DECLARE acUsageInMins INT;

	DECLARE doorMsg VARCHAR(100) DEFAULT '';
	DECLARE isDoorOpen TINYINT(1) DEFAULT NULL;
	DECLARE doorSelect INT;
	DECLARE doorTime DATETIME;
	DECLARE isExtBattDoorVar, doorDigitalIOVar TINYINT(1);
	DECLARE extBattAnalogValue INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
		/*
		GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
		*/
		SET isUpdated = 0;
	END;
	/*
		@Var are loosely typed variables that may be initialized somewhere in a session and keep their value until the session ends
		According to stackoverflow article, they are similar to static variables and have scope upto the program ends.
		We are using them to define the SMS message templates and other static values like event type.
	*/
	SET @tamperYesMsg = CONCAT(vehicleNoParam, ' underwent tampering');
	SET @tamperNormalMsg = CONCAT(vehicleNoParam, ' is back to normal');
	SET @tamperAlertType = 7;

	SET @powercutYesMsg = CONCAT(vehicleNoParam, ' underwent powercut');
	SET @powercutNormalMsg = CONCAT(vehicleNoParam, ' regained power');
	SET @powercutAlertType = 6;

	SET @acOnMsg = CONCAT(vehicleNoParam, ' has switched on AC at ');
	SET @acOffMsg = CONCAT(vehicleNoParam, ' has switched off AC at ');
	SET @acAlertType = 1;

	SET @doorOpenMsg = CONCAT(vehicleNoParam, ' has opened the door at ');
	SET @doorClosedMsg = CONCAT(vehicleNoParam, ' has closed the door at ');
	SET @doorAlertType = 16;

	SET @panicMsg =  CONCAT(vehicleNoParam, ' has sent a panic alarm. To immobilize, send ttysms,STOPV to ', simcardnoParam, '. To restart, send ttysms,STARTV');
	SET @panicAlertType = 15;

	IF digitalioupdatedParam = '0000-00-00 00:00:00' THEN
		SET digitalioupdatedParam = NULL;
	END IF;


	START TRANSACTION;
		/******************** <TAMPER> *********************/
		IF tamperParam = 1 AND tamperStatusParam = 0 THEN
			SET tamperMsg 	= 	@tamperYesMsg;
			SET isTampered  = 	1;
		ELSEIF tamperParam = 0 AND tamperStatusParam = 1 THEN
			SET tamperMsg 	= 	@tamperNormalMsg;
			SET isTampered  = 	0;
		END IF;
		IF tamperMsg != '' THEN
			BEGIN
				INSERT INTO comqueue
					(customerno, vehicleid, devlat, devlong, `type`, `status`, message, timeadded)
				VALUES
					(custnoParam, vehicleidParam, devicelatParam, devicelongParam, @tamperAlertType, tamperStatusParam, tamperMsg, todaysDate);

				UPDATE 	eventalerts
				SET 	tamper		= 	isTampered
				WHERE 	vehicleid	=	vehicleIdParam
				AND 	customerno	=	custnoParam;
			END;
		END IF;
		/******************** </TAMPER> *********************/

		/******************** <POWERCUT> *********************/
		IF powercutParam = 0 AND powercutStatusParam = 0 THEN
			SET powercutMsg 	= 	@powercutYesMsg;
			SET isPowercut 		= 	1;
		ELSEIF powercutParam = 1 AND powercutStatusParam = 1 THEN
			SET powercutMsg 	= 	@powercutNormalMsg;
			SET isPowercut 		= 	0;
		END IF;
		IF powercutMsg != '' THEN
			BEGIN
				INSERT INTO comqueue
					(customerno, vehicleid, devlat, devlong, `type`, `status`, message, timeadded)
				VALUES
					(custnoParam, vehicleidParam, devicelatParam, devicelongParam, @powercutAlertType, powercutStatusParam, powercutMsg, todaysDate);

				UPDATE 	eventalerts
				SET 	powercut	= 	isPowercut
				WHERE 	vehicleid	=	vehicleIdParam
				AND 	customerno	=	custnoParam;
			END;
		END IF;
		/******************** </POWERCUT> *********************/

		/******************** <OVERSPEED> *********************/

                CALL insert_overspeed_alert(vehicleidParam
                                    ,custnoParam
                                    ,speedParam
                                    ,overspeedLimitParam
                                    ,devicelatParam
                                    ,devicelongParam
                                    ,overspeedStatusParam
                                    ,vehicleNoParam
                                    ,todaysDate);

		/******************** </OVERSPEED> *********************/

		/******************** <IMMOBILIZER> *********************/
		IF commandkeyParam = 21 THEN
			#IMMOBILIZER - START
			SET isVehicleImmobilized	=	1;
		ELSEIF commandkeyParam = 20 THEN
			#IMMOBILIZER - STOP
			SET isVehicleImmobilized	=	0;
		END IF;
		IF isVehicleImmobilized IS NOT NULL THEN
			BEGIN
				UPDATE 	unit
				SET 	mobiliser_flag 	= 	isVehicleImmobilized
				WHERE 	vehicleid		=	vehicleIdParam
				AND 	customerno		=	custnoParam;
			END;
		END IF;
		/******************** </IMMOBILIZER> *********************/

		/******************** <AC SENSOR> *********************/
		IF acsensorParam = 1 AND statusParam != 'F'	AND digitalioupdatedParam IS NOT NULL THEN
			BEGIN
				IF digitalioParam = 0 and acStatusParam = 0 THEN
					BEGIN
						SET acCountParam	= 	acCountParam + 1;
						IF acCountParam = 1 THEN
							SET acTimeParam = todaysdate;
						ELSEIF acCountParam = 5 THEN
							SET acCountParam = 0;
							IF is_ac_oppParam = 0 THEN
								BEGIN
									SET acMsg 	= 	CONCAT(@acOnMsg, DATE_FORMAT(acTimeParam, '%I:%m %p'));
									SET isAcOn	=	1;
								END;
							ELSEIF is_ac_oppParam = 1 THEN
								BEGIN
									SET acMsg 	= 	CONCAT(@acOffMsg, DATE_FORMAT(acTimeParam, '%I:%m %p'));
									SET isAcOn	=	0;
								END;
							END IF;
						END IF;
					END;
				ELSEIF digitalioParam = 1 and acStatusParam = 1 THEN
					BEGIN
						SET acCountParam	= 	acCountParam + 1;
						IF acCountParam = 1 THEN
							SET acTimeParam = todaysdate;
						ELSEIF acCountParam = 5 THEN
							SET acCountParam = 0;
							IF is_ac_oppParam = 0 THEN
								BEGIN
									SET acMsg 	= 	CONCAT(@acOffMsg, DATE_FORMAT(acTimeParam, '%I:%m %p'));
									SET isAcOn	=	0;
								END;
							ELSEIF is_ac_oppParam = 1 THEN
								BEGIN
									SET acMsg 	= 	CONCAT(@acOnMsg, DATE_FORMAT(acTimeParam, '%I:%m %p'));
									SET isAcOn	=	1;
								END;
							END IF;
						END IF;
					END;
				ELSEIF (digitalioParam = 0 and acStatusParam = 1) OR (digitalioParam = 1 and acStatusParam = 0) THEN
					BEGIN
						SET acCountParam = 0;
						SET acTimeParam = todaysdate;
					END;
				END IF;

				IF digitalioParam = 0 OR digitalioParam = 1 OR acStatusParam = 0 OR acStatusParam = 1 THEN
					BEGIN
						IF acMsg != "" THEN
							BEGIN
								INSERT INTO comqueue
								(customerno, vehicleid, devlat, devlong, `type`, `status`, message, timeadded)
								VALUES
								(custnoParam, vehicleidParam, devicelatParam, devicelongParam, @acAlertType, isAcOn, acMsg, todaysDate);

								IF isAcOn = 0 THEN
									BEGIN
										SET acUsageInMins = CAST(ROUND(TIMESTAMPDIFF(MINUTE, digitalioupdatedParam, todaysDate)) AS SIGNED);
										UPDATE 	dailyreport
										SET 	acusage = acusage + acUsageInMins
										WHERE 	vehicleid = vehicleidParam
										AND 	customerno = custnoParam;
									END;
								END IF;

								UPDATE 	eventalerts
								SET 	ac = NOT acStatusParam
								WHERE 	vehicleid = vehicleidParam
								AND 	customerno = custnoParam;

								UPDATE 	unit
								SET 	digitalioupdated = acTimeParam
								WHERE 	vehicleid = vehicleidParam
								AND 	customerno = custnoParam;
							END;
						END IF;

						UPDATE 	eventalerts
						SET 	ac_count = acCountParam
								, ac_time = acTimeParam
						WHERE 	vehicleid = vehicleidParam
						AND 	customerno = custnoParam;
					END;
				END IF;
			END;
		END IF;
		/******************** </AC SENSOR> *********************/

		/******************** <DOOR SENSOR> *********************/
		IF doorsensorParam = 1 and statusParam != 'F' THEN
			BEGIN
				/* Door sensor value comes as analog value in external battery for some units */
				/* If external battery gives the door value and if door digital io is 1, then door is open */
				SET isExtBattDoorVar = 0;
				SELECT 	isDoorExt, door_digitalio
				INTO	isExtBattDoorVar, doorDigitalIOVar
				FROM 	unit
				WHERE 	vehicleid = vehicleidParam
				AND 	customerno = custnoParam
				LIMIT 	1;

				IF swvParam = 'E10.14TCAD' THEN
					SET doorSelect = analog4;
				ELSEIF isExtBattDoorVar = 1 THEN
					SET doorSelect = CAST(doorDigitalIOVar AS SIGNED);
				ELSE
					SET doorSelect = CAST(digitalioParam AS SIGNED);
				END IF;

				IF doorSelect = 0 AND door_statusParam = 0 THEN
					BEGIN
						SET doorTime = todaysDate;
						IF is_door_oppParam = 0 THEN
							SET doorMsg = CONCAT(@doorClosedMsg, DATE_FORMAT(doorTime, '%I:%m %p'));
							SET isDoorOpen = 0;
						ELSEIF is_door_oppParam = 1 THEN
							SET doorMsg = CONCAT(@doorOpenMsg, DATE_FORMAT(doorTime, '%I:%m %p'));
							SET isDoorOpen = 1;
						END IF;
					END;
				ELSEIF doorSelect = 1 and door_statusParam = 1 THEN
					BEGIN
						SET doorTime = todaysDate;
						IF is_door_oppParam = 0 THEN
							SET doorMsg = CONCAT(@doorOpenMsg, DATE_FORMAT(doorTime, '%I:%m %p'));
							SET isDoorOpen = 1;
						ELSEIF is_door_oppParam = 1 THEN
							SET doorMsg = CONCAT(@doorClosedMsg, DATE_FORMAT(doorTime, '%I:%m %p'));
							SET isDoorOpen = 0;
						END IF;
					END;
				END IF;

				IF doorSelect = 0 OR doorSelect = 1 OR door_statusParam = 0 OR door_statusParam = 1 THEN
					BEGIN
						IF doorMsg != "" THEN
							BEGIN
								INSERT INTO comqueue
								(customerno, vehicleid, devlat, devlong, `type`, `status`, message, timeadded)
								VALUES
								(custnoParam, vehicleidParam, devicelatParam, devicelongParam, @doorAlertType, isDoorOpen, doorMsg, todaysDate);

								UPDATE 	eventalerts
								SET 	door = NOT door_statusParam
								WHERE 	vehicleid = vehicleidParam
								AND 	customerno = custnoParam;

								UPDATE 	unit
								SET 	door_digitalioupdated = doorTime
								WHERE 	vehicleid = vehicleidParam
								AND 	customerno = custnoParam;
							END;
						END IF;

						UPDATE 	eventalerts
						SET 	door_time = doorTime
						WHERE 	vehicleid = vehicleidParam
						AND 	customerno = custnoParam;
					END;
				END IF;
			END;
		END IF;

		/******************** </DOOR SENSOR> *********************/

		/******************** <PANIC> *********************/

		IF statusParam = 'N' AND simcardnoParam IS NOT NULL AND simcardnoParam != '' THEN
			INSERT INTO comqueue
			(customerno, vehicleid, devlat, devlong, `type`, `status`, message, timeadded)
			VALUES
			(custnoParam, vehicleidParam, devicelatParam, devicelongParam, @panicAlertType, 1, @panicMsg, todaysDate);
		END IF;

		/******************** </PANIC> *********************/

		SET isUpdated = 1;
	COMMIT;
END$$
DELIMITER ;


UPDATE dbpatches SET isapplied=1 WHERE patchid = 570;