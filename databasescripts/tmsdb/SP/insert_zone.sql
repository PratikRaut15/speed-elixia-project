DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_zone`$$
CREATE  PROCEDURE `insert_zone`( 
	IN zonename VARCHAR (25)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT 
    , OUT currentzoneid INT
	)
BEGIN

	INSERT INTO zone(
						zonename
						, customerno
						, created_on
						, updated_on
                        , created_by
                        , updated_by
					)
	VALUES ( 
				zonename
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentzoneid = LAST_INSERT_ID();

END$$
DELIMITER ;
