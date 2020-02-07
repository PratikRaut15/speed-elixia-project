DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_vehicletype`$$
CREATE  PROCEDURE `delete_vehicletype`(
	IN vehtypeid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE vehicletype 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE vehicletypeid = vehtypeid;
END$$
DELIMITER ;
