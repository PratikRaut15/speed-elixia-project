DELIMITER $$
DROP PROCEDURE IF EXISTS update_transportershare$$
CREATE PROCEDURE `update_vehicle`(
	IN vehid INT
    , IN vehicleno VARCHAR(20)
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE vehicle 
    SET  vehicletypeid = vehicletypeid
		, vehicleno = vehicleno
		, updated_on = todaysdate
        , updated_by = userid
	WHERE vehicleid = vehid;
END$$
DELIMITER ;