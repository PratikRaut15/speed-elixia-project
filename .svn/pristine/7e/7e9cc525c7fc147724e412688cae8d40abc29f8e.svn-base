DELIMITER $$
DROP PROCEDURE IF EXISTS update_location$$
CREATE PROCEDURE `update_location`( 
	IN locationname VARCHAR (50)
	, IN locid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE location 
    SET  locationname = locationname
		, updated_on = todaysdate
        , updated_by = userid
	WHERE locationid = locid;
END$$
DELIMITER ;