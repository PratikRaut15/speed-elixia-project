DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_routemaster`$$
CREATE  PROCEDURE `insert_routemaster`( 
	IN routename VARCHAR(20)
	,IN routedescription VARCHAR(50)
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN travellingtime INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroutemasterid INT
	)
BEGIN
	INSERT INTO routemaster(
							routename
				, routedescription                            
				, fromlocationid
                            , tolocationid
                            , distance
                            , travellingtime
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				routename
				, routedescription				
				, fromlocationid
				, tolocationid
				, distance
                , travellingtime
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentroutemasterid = LAST_INSERT_ID();

END$$
DELIMITER ;
