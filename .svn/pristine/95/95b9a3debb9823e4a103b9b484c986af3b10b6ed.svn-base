DELIMITER $$
DROP PROCEDURE IF EXISTS `get_locations`$$
CREATE  PROCEDURE `get_locations`(
	IN custno INT
    , IN locid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(locid = '' OR locid = 0) THEN
		SET locid = NULL;
	END IF;
	SELECT locationid
			, locationname
			, customerno
			, created_on
			, updated_on
   FROM location
   WHERE (customerno = custno OR custno IS NULL)
   AND	(locationid = locid OR locid IS NULL)
   AND isdeleted = 0;
END$$
DELIMITER ;