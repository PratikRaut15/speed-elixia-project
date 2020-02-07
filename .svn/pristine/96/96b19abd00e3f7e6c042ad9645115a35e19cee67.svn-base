DELIMITER $$
DROP PROCEDURE IF EXISTS `edit_travel_settings`$$
CREATE PROCEDURE `edit_travel_settings`(
  IN travelSettingIdParam TIME
  ,IN starttimeParam TIME
	,IN endtimeParam TIME
  ,IN thresholdParam INT
  ,IN customernoParam INT
	,IN updatedOnParam DATETIME
  ,IN updatedByParam INT
	,IN isDeletedParam INT
	)
BEGIN
	DECLARE varTravelSettingsId INT;

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
   
      UPDATE `night_drive_details` 
      SET `start_time`      = starttimeParam
      , `end_time`          = endtimeParam 
      ,`threshold_distance` = thresholdParam 
      , `updatedBy`         = updatedOnParam 
      , `updatedOn`         = updatedOnParam
      ,`isDeleted`          = isDeletedParam 
      WHERE `nightDriveDetId`= travelSettingIdParam AND `customerno`  = customernoParam;  
      
  	END;
  	COMMIT;

   SET varTravelSettingsId = LAST_INSERT_ID();
   select varTravelSettingsId;
END$$
DELIMITER ;
