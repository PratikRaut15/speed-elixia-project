




/*
    Name			-	insertTempSensorSpecificAlert
    Description 	-	to Insert entry in alertTempUserMapping table
    Parameters		-	
    Module			-	Temperature Alert
    Created by		-	Arvind Thakur
    Created on		- 	24 Sep, 2019
*/
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

 CALL insertTempSensorSpecificAlert('2019-09-24 12:00:00');