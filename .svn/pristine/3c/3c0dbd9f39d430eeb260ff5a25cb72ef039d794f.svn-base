DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vehicletype`$$
CREATE  PROCEDURE `insert_vehicletype`( 
	IN vehiclecode VARCHAR (20)
	, IN vehicledescription VARCHAR (50)
, IN tid INT (11)
    , IN volume VARCHAR (50)
    , IN weight VARCHAR (15)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentvehicletypeid INT
	)
BEGIN
	INSERT INTO vehicletype(
							vehiclecode
                            , vehicledescription
		 	    , skutypeid
                            , volume
							, weight
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				vehiclecode
                , vehicledescription
		, tid
                , volume
				, weight
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentvehicletypeid = LAST_INSERT_ID();

END$$
DELIMITER ;
