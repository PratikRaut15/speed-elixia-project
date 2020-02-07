DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_location`$$
CREATE PROCEDURE `insert_location`( 
	IN locationname VARCHAR (50)
	,IN customerno INT
    ,IN todaysdate DATETIME
    ,IN userid INT
    ,OUT currentlocationid INT
	)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	  BEGIN
		-- ERROR
	  ROLLBACK;
	END;
	START TRANSACTION;
	INSERT INTO location (
							locationname
							, customerno
							, created_on
							, updated_on
							, created_by
							, updated_by
						)
	VALUES ( 
				locationname
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
	SET currentlocationid = LAST_INSERT_ID();
    
    COMMIT;	
END$$
DELIMITER ;