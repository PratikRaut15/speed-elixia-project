DELIMITER $$
DROP PROCEDURE IF EXISTS update_vehicletype$$
CREATE PROCEDURE `update_vehicletype`( 
	IN vehiclecode VARCHAR(20)
	, IN vehicledescription VARCHAR (50)
	, IN tid INT (11)
    , IN volume decimal(7,3)
    , IN weight decimal(7,3)
	, IN vehtypeid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE vehicletype
    SET  vehiclecode = vehiclecode
		, vehicledescription = vehicledescription
		, skutypeid = tid
        , volume = volume
		, weight = weight
        , updated_on = todaysdate
        , updated_by = userid
	WHERE vehicletypeid = vehtypeid;
END$$
DELIMITER ;