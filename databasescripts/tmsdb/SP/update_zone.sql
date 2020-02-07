DELIMITER $$
DROP PROCEDURE IF EXISTS update_zone$$
CREATE PROCEDURE `update_zone`( 
	IN zid INT
	,IN zonename VARCHAR (25)
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	/* DECLARE noOfRowsAffected INT; 
    START TRANSACTION;*/
	UPDATE zone 
	SET  zonename = zonename
		, updated_on = todaysdate
		, updated_by = userid
	WHERE zoneid = zid;
        /*
	SET noOfRowsAffected = ROW_COUNT();

	IF(noOfRowsAffected = 1) THEN COMMIT;
		ELSE ROLLBACK;
	END IF;    
        */
END$$
DELIMITER ;