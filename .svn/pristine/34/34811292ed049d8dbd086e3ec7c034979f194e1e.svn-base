DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_indent_sku_mapping`$$
CREATE PROCEDURE `delete_indent_sku_mapping`( 
	IN ismid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE  indent_sku_mapping
	SET 	isdeleted =1
			, updated_on = todaysdate
			, updated_by = userid
	WHERE 	indent_sku_mappingid = ismid;

END$$
DELIMITER ;
