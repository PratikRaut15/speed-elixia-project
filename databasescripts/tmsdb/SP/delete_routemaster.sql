DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_routemaster`$$
CREATE PROCEDURE `delete_routemaster`(
	IN rtmasterid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routemaster 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE routemasterid = rtmasterid;
END$$
DELIMITER ;
