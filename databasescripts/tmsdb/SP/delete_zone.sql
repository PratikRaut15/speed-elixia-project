DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_zone`$$
CREATE  PROCEDURE `delete_zone`(
	IN zid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	
	UPDATE zone 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE zoneid = zid;
     
END$$
DELIMITER ;
