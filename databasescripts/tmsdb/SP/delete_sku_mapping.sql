DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_sku_mapping`$$
CREATE PROCEDURE `delete_sku_mapping`( 
	IN skumapid int
    , IN propindentid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF (skumapid = '' OR skumapid = 0) THEN
		SET skumapid = NULL;
    END IF;
    IF (propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
    END IF;
    
    
	UPDATE  proposed_indent_sku_mapping
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(proposed_indent_sku_mappingid = skumapid	OR skumapid IS NULL)
    AND 	(proposedindentid = propindentid OR propindentid IS NULL);

END$$
DELIMITER ;
