/*
    Name			    -	get_travelsetting_list
    Description 	-	To Get list of travelsettings that have been added per customer
    Parameters		-	customerno
    Module			  -	travelSettings Masters
    Created by		-	Manasvi Thakur
    Created on		- 2 April 2019
*/
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
