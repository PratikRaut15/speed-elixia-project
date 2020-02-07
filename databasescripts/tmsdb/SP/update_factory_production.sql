DELIMITER $$
DROP PROCEDURE IF EXISTS update_factory_production$$
CREATE PROCEDURE `update_factory_production`( 
	IN fpidparam int
	,IN factoryid INT
	,IN skuid int
	,IN weight float(6,2)
	,IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	factory_production
	SET	 	factoryid=factoryid,
			skuid = skuid,
			weight = weight,
			updated_on = todaysdate 
			, updated_by = userid
	WHERE 	fpid = fpidparam;

END$$
DELIMITER ;