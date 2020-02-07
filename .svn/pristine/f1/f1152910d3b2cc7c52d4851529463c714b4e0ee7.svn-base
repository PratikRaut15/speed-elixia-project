DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory_production`$$
CREATE  PROCEDURE `insert_factory_production`( 
	IN factoryid int
	, IN skuid int
	, IN weight float(6,2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentfpid INT
)
BEGIN

	INSERT INTO factory_production( 
									factoryid
									, skuid
                                    , weight
                                    , customerno
                                    , created_on
                                    , updated_on
                                    , created_by
                                    , updated_by
                                    ) 
	VALUES	(
					factoryid
					, skuid 
					, weight
					, customerno
					, todaysdate
					, todaysdate
					, userid
					, userid
			);

	SET currentfpid = LAST_INSERT_ID();
    
END$$
DELIMITER ;
