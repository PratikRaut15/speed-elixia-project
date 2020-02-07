DELIMITER $$
DROP PROCEDURE IF EXISTS  `delete_factory_delivery`$$
CREATE PROCEDURE `delete_factory_delivery`( 
	IN fdidparam int
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	factory_delivery
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE	fdid = fdidparam;

END$$
DELIMITER ;
