DELIMITER $$
DROP PROCEDURE IF EXISTS update_factory$$
CREATE PROCEDURE `update_factory`( 
	  IN factorycode VARCHAR (10)
	,IN factoryname VARCHAR (50)
    , IN zoneid INT
	, IN fid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE factory 
    SET  factorycode = factorycode
	,factoryname = factoryname	
	, zoneid = zoneid
        , updated_on = todaysdate
        , updated_by = userid
	WHERE factoryid = fid;
END
$$
DELIMITER ;
