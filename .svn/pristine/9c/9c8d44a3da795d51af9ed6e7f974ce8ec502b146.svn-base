DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_location`$$
CREATE PROCEDURE `delete_location`(
	IN locid INT
	, IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
	UPDATE location 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE locationid = locid;
END$$
DELIMITER ;
