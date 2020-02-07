DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_travel_settings`$$
CREATE PROCEDURE `insert_travel_settings`(
	IN starttimeParam TIME
    ,IN endtimeParam TIME
    ,IN customernoParam INT
	,IN createdOnParam DATETIME
	,IN createdByParam INT
	,IN updatedOnParam DATETIME
	,IN updatedByParam INT
	)
BEGIN
	DECLARE varTravelsettinId INT;

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
	IF(customernoParam = '' OR customernoParam = 0) THEN
		SET customernoParam = NULL;
	END IF;
   
   INSERT INTO `night_drive_details` (`start_time`, `end_time`, `threshold_distance`, `customerno`, `createdBy`, `createdOn`, `updatedBy`, `updatedOn`, `isDeleted`) 
   VALUES
   		(starttimeParam,endtimeParam,customernoParam,createdOnParam,createdByParam,updatedOnParam,updatedByParam)
	END;
	COMMIT;

   SET varTravelsettinId = LAST_INSERT_ID();
   select varTravelsettinId;
END$$
DELIMITER ;
