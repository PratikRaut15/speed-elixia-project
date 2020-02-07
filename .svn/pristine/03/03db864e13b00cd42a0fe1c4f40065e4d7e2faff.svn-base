DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_factory`$$
CREATE PROCEDURE `delete_factory`(
	IN fid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE factory 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE factoryid = fid;
END$$
DELIMITER ;
