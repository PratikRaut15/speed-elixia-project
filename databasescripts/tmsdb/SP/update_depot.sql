DELIMITER $$
DROP PROCEDURE IF EXISTS update_depot$$
CREATE PROCEDURE `update_depot`( 
	IN depotcode VARCHAR(20)
	, IN depotname VARCHAR (50)
	, IN zoneid INT
    	, IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE depot
    SET  depotcode = depotcode
		, depotname = depotname
        , zoneid = zoneid
		
        , updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;

END$$
DELIMITER ;
