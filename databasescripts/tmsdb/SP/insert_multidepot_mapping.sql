DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_multidepot_mapping`$$
CREATE PROCEDURE `insert_multidepot_mapping`( 
	IN depotidparam INT
    , IN factoryidparam INT
	, IN multidepotidparam VARCHAR(50)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
	INSERT INTO multidepot_mapping(
							depotid
							, factoryid
                            , depotmappingid
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				depotidparam
				, factoryidparam
                , multidepotidparam
                , customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
END$$
DELIMITER ;
