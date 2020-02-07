DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_depot`$$
CREATE PROCEDURE `insert_depot`( 
	IN depotcode VARCHAR (20)
	, IN depotname VARCHAR (50)
    , IN zoneid INT
    , IN multidrop INT
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentdepotid INT
	)
BEGIN
	INSERT INTO depot(
							depotcode
                            , depotname
                            , zoneid
                            , multidrop
							, customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				depotcode
                , depotname
                , zoneid
                , multidrop
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentdepotid = LAST_INSERT_ID();

END$$
DELIMITER ;
