SET @patchId = 726;
SET @patchDate = '2019-09-25 18:30:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'Insert alertTempUserMapping SP';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `insertAlertTempUserMapping`$$
CREATE PROCEDURE `insertAlertTempUserMapping`(
	IN todaysDateParam DATETIME
	)
BEGIN

  ROLLBACK;
  BEGIN
    /*
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error;
      */

    END;
   START TRANSACTION;
   BEGIN
   
   	INSERT INTO alertTempUserMapping(uid
	        , vehicleid
	        , userid
	        , temp1_intv 
	        , temp2_intv
	        , temp3_intv
	        , temp4_intv
                , insertedon
                , insertedby
                , updatedby
	        , customerno)
	SELECT  coalesce(un.uid,0)
	        , un.vehicleid
	        ,u.userid
	        , un.temp1_intv 
	        , un.temp2_intv
	        , un.temp3_intv
	        , un.temp4_intv
                , todaysDateParam
                , 0
                , 0
	        , u.customerno
	FROM    `user` u 
	INNER JOIN unit un ON un.customerno = u.customerno
	INNER JOIN vehiclewise_alert va ON va.customerno = un.customerno 
			AND va.vehicleid = un.vehicleid 
			AND va.userid = u.userid 
	LEFT OUTER JOIN alertTempUserMapping atum ON atum.uid = un.uid 
                        AND atum.vehicleid = un.vehicleid
                        AND atum.userid = u.userid
	WHERE   u.isdeleted = 0
	AND     atum.atumid IS NULL;

        SELECT LAST_INSERT_ID() AS lastId;

    END;
    COMMIT;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insertTempSensorSpecificAlert`$$
CREATE PROCEDURE `insertTempSensorSpecificAlert`(
	IN todaysDateParam DATETIME
	)
BEGIN

  ROLLBACK;
  BEGIN
    /*
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error;
      */

    END;
   START TRANSACTION;
   BEGIN
   
   	INSERT INTO tempSensorSpecificAlert(userid
            ,tempSensor1
            ,tempSensor2
            ,tempSensor3
            ,tempSensor4
            ,customerno
            ,created_on)
        SELECT  userid
                ,1
                ,1
                ,1
                ,1
                ,a.customerno
                ,todaysDateParam
        FROM 	user a
        INNER JOIN customer on customer.customerno = a.customerno
        WHERE 	(a.temp_email != '0' OR a.temp_sms != '0' OR a.temp_telephone != '0' OR a.temp_mobilenotification!='0')
        AND 	customer.temp_sensors != 0
        AND 	a.isdeleted = 0
        AND 	customer.customerno IN (SELECT customerno FROM customer where temp_sensors > 0 and customerno NOT IN (1))
        AND 	a.userid NOT IN (select userid from tempSensorSpecificAlert where isdeleted = 0);

        SELECT LAST_INSERT_ID() AS lastId;

    END;
    COMMIT;

END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
