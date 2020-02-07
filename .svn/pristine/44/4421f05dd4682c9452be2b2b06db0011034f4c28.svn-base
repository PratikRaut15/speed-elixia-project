



/*
    Name			-	insertAlertTempUserMapping
    Description 	-	to Insert entry in alertTempUserMapping table
    Parameters		-	
    Module			-	Temperature Alert
    Created by		-	Arvind Thakur
    Created on		- 	24 Sep, 2019
*/
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

--  CALL insertAlertTempUserMapping('2019-09-24 12:00:00');