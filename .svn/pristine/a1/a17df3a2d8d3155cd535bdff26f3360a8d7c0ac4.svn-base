DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_depot`$$
CREATE PROCEDURE `delete_depot`(
	IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE depot 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;
END$$
DELIMITER ;

