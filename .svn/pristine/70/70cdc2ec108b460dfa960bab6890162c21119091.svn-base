DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_routecheckpoint`$$
CREATE  PROCEDURE `insert_routecheckpoint`( 
	IN routemasterid INT	  
	,IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroutecheckpointid INT
	)
BEGIN
	INSERT INTO routecheckpoint(
							routemasterid
                            , fromlocationid
                            , tolocationid
                            , distance
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				routemasterid
				, fromlocationid
				, tolocationid
				, distance
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentroutecheckpointid = LAST_INSERT_ID();

END$$
DELIMITER ;
