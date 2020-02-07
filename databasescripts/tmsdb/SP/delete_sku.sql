DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_sku`$$
CREATE PROCEDURE `delete_sku`( 
	IN skuidparam INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	sku
	SET  	isdeleted = 1
			, updated_on  = todaysdate
            , updated_by = userid
	WHERE	skuid = skuidparam;

END$$
DELIMITER ;
