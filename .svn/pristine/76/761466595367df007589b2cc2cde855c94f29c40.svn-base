INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`)
VALUES ('701', '2019-04-05 17:50:00', 'Manasvi Thakur','Travel Settings SPs', '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_travelsetting_list`$$
CREATE PROCEDURE `get_travelsetting_list`(
	IN customernoParam INT
	,IN travelSettingsIdParam INT
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
    IF(customernoParam = '' OR customernoParam = 0) THEN
		SET customernoParam = NULL;
	END IF;

   IF(travelSettingsIdParam = 0)THEN
	   SELECT *
	   FROM night_drive_details 
	   WHERE (customerno = customernoParam OR customernoParam IS NULL)
	   AND isdeleted = 0;
	END IF;

END$$
DELIMITER ;

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
   		(starttimeParam,endtimeParam,customernoParam,createdOnParam,createdByParam,updatedOnParam,updatedByParam);
	END;
	COMMIT;

   SET varTravelsettinId = LAST_INSERT_ID();
   select varTravelsettinId;
END$$
DELIMITER ;

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

UPDATE  dbpatches
SET     updatedOn = '2019-04-01 14:50:00'
        ,isapplied = 1
WHERE   patchid = 701;