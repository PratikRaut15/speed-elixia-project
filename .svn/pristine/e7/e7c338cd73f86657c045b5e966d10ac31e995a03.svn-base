DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_factory_production`$$
CREATE PROCEDURE `delete_factory_production`( 
	IN fpidparam int
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	factory_production
	SET 	isdeleted =1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	fpid = fpidparam;

END$$
DELIMITER ;
